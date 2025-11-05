<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of unique customers from orders.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Filter by customer phone if provided
        if ($request->has('phone')) {
            $query->where('customer_phone', 'like', '%' . $request->phone . '%');
        }

        // Filter by customer name if provided
        if ($request->has('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->name . '%')
                  ->orWhere('customer_first_name', 'like', '%' . $request->name . '%')
                  ->orWhere('customer_last_name', 'like', '%' . $request->name . '%');
            });
        }

        // Get unique customers grouped by phone (primary identifier) or name
        $customers = $query
            ->select([
                DB::raw('COALESCE(NULLIF(customer_phone, ""), customer_name) as identifier'),
                DB::raw('MAX(customer_name) as customer_name'),
                DB::raw('MAX(customer_first_name) as customer_first_name'),
                DB::raw('MAX(customer_last_name) as customer_last_name'),
                DB::raw('MAX(customer_phone) as customer_phone'),
                DB::raw('MAX(customer_address) as customer_address'),
                DB::raw('MAX(customer_city) as customer_city'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('MAX(created_at) as last_order_date'),
            ])
            ->where(function ($q) {
                $q->where(function ($subQ) {
                    $subQ->whereNotNull('customer_phone')
                         ->where('customer_phone', '!=', '');
                })
                ->orWhere(function ($subQ) {
                    $subQ->whereNotNull('customer_name')
                         ->where('customer_name', '!=', '');
                });
            })
            ->groupBy('identifier')
            ->orderBy('last_order_date', 'desc')
            ->paginate($request->get('per_page', 20));

        // Transform to customer format
        $customerData = $customers->getCollection()->map(function ($customer) {
            return [
                'id' => $customer->identifier,
                'name' => $customer->customer_name ?: trim(($customer->customer_first_name ?? '') . ' ' . ($customer->customer_last_name ?? '')),
                'first_name' => $customer->customer_first_name,
                'last_name' => $customer->customer_last_name,
                'phone' => $customer->customer_phone,
                'address' => $customer->customer_address,
                'city' => $customer->customer_city,
                'order_count' => $customer->order_count,
                'last_order_date' => $customer->last_order_date,
            ];
        });

        return response()->json([
            'data' => $customerData,
            'links' => [
                'first' => $customers->url(1),
                'last' => $customers->url($customers->lastPage()),
                'prev' => $customers->previousPageUrl(),
                'next' => $customers->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $customers->currentPage(),
                'from' => $customers->firstItem(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'to' => $customers->lastItem(),
                'total' => $customers->total(),
            ],
        ]);
    }

    /**
     * Get customer details including all orders.
     */
    public function show(Request $request, string $identifier)
    {
        // Find customer by phone or name
        $orders = Order::where('customer_phone', $identifier)
            ->orWhere('customer_name', $identifier)
            ->orWhere(function ($q) use ($identifier) {
                $parts = explode(' ', $identifier, 2);
                if (count($parts) === 2) {
                    $q->where('customer_first_name', $parts[0])
                      ->where('customer_last_name', $parts[1]);
                }
            })
            ->with(['lines', 'blinds'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $firstOrder = $orders->first();

        return response()->json([
            'data' => [
                'id' => $identifier,
                'name' => $firstOrder->customer_name ?: trim(($firstOrder->customer_first_name ?? '') . ' ' . ($firstOrder->customer_last_name ?? '')),
                'first_name' => $firstOrder->customer_first_name,
                'last_name' => $firstOrder->customer_last_name,
                'phone' => $firstOrder->customer_phone,
                'address' => $firstOrder->customer_address,
                'city' => $firstOrder->customer_city,
                'order_count' => $orders->count(),
                'orders' => $orders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'reference' => $order->reference,
                        'status' => $order->status,
                        'total_amount' => $order->total_amount,
                        'created_at' => $order->created_at,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Update customer information across all their orders.
     */
    public function update(Request $request, string $identifier)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
        ]);

        // Find all orders for this customer
        $orders = Order::where('customer_phone', $identifier)
            ->orWhere('customer_name', $identifier)
            ->orWhere(function ($q) use ($identifier) {
                $parts = explode(' ', $identifier, 2);
                if (count($parts) === 2) {
                    $q->where('customer_first_name', $parts[0])
                      ->where('customer_last_name', $parts[1]);
                }
            })
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // Normalize phone number if provided
        $phone = $validated['phone'] ?? null;
        if (!empty($phone)) {
            // Remove all non-digit characters except +
            $phone = preg_replace('/[^\d+]/', '', $phone);
            // Ensure it starts with + if it doesn't already
            if (!empty($phone) && !str_starts_with($phone, '+')) {
                $phone = '+' . $phone;
            }
        }

        // Update all orders
        $updated = 0;
        foreach ($orders as $order) {
            if (isset($validated['name'])) {
                $order->customer_name = $validated['name'];
            }
            if (isset($validated['first_name'])) {
                $order->customer_first_name = $validated['first_name'];
            }
            if (isset($validated['last_name'])) {
                $order->customer_last_name = $validated['last_name'];
            }
            if (isset($phone)) {
                $order->customer_phone = $phone;
            }
            if (isset($validated['address'])) {
                $order->customer_address = $validated['address'];
            }
            if (isset($validated['city'])) {
                $order->customer_city = $validated['city'];
            }
            $order->save();
            $updated++;
        }

        // Get updated customer info
        $firstOrder = $orders->first()->fresh();
        $newIdentifier = $phone ?: ($validated['name'] ?? $identifier);

        return response()->json([
            'message' => "Customer information updated successfully for {$updated} order(s).",
            'data' => [
                'id' => $newIdentifier,
                'name' => $firstOrder->customer_name ?: trim(($firstOrder->customer_first_name ?? '') . ' ' . ($firstOrder->customer_last_name ?? '')),
                'first_name' => $firstOrder->customer_first_name,
                'last_name' => $firstOrder->customer_last_name,
                'phone' => $firstOrder->customer_phone,
                'address' => $firstOrder->customer_address,
                'city' => $firstOrder->customer_city,
                'order_count' => $orders->count(),
                'updated_orders' => $updated,
            ],
        ]);
    }
}
