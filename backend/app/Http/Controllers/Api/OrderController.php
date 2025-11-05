<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['lines', 'blinds']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Handle search across multiple fields (phone, reference, name) with OR logic
        // This is used when a short numeric search could match multiple fields
        $hasPhoneSearch = $request->has('customer_phone');
        $hasReferenceSearch = $request->has('reference');
        $hasNameSearch = $request->has('customer_name');
        
        // If we have multiple search types, combine them with OR
        if (($hasPhoneSearch && $hasReferenceSearch) || 
            ($hasPhoneSearch && $hasNameSearch) || 
            ($hasReferenceSearch && $hasNameSearch)) {
            
            $query->where(function ($q) use ($request, $hasPhoneSearch, $hasReferenceSearch, $hasNameSearch) {
                // Phone search
                if ($hasPhoneSearch) {
                    $phoneSearch = $request->customer_phone;
                    $normalizedPhone = preg_replace('/[^\d+]/', '', $phoneSearch);
                    
                    $q->where(function ($phoneQ) use ($phoneSearch, $normalizedPhone) {
                        $phoneQ->where('customer_phone', 'like', '%' . $phoneSearch . '%');
                        if (strlen($normalizedPhone) > 0) {
                            if ($normalizedPhone !== $phoneSearch) {
                                $phoneQ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(customer_phone, ' ', ''), '-', ''), '(', ''), ')', ''), '+', '') LIKE ?", ['%' . $normalizedPhone . '%']);
                            }
                            $phoneQ->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(customer_phone, ' ', ''), '-', ''), '(', ''), ')', ''), '+', '') LIKE ?", ['%' . $phoneSearch . '%']);
                        }
                    });
                }
                
                // Reference search
                if ($hasReferenceSearch) {
                    $searchTerm = '%' . $request->reference . '%';
                    $q->orWhere(function ($refQ) use ($searchTerm) {
                        $refQ->where('reference', 'like', $searchTerm)
                             ->orWhere('id', 'like', $searchTerm);
                    });
                }
                
                // Name search
                if ($hasNameSearch) {
                    $searchTerm = '%' . $request->customer_name . '%';
                    $q->orWhere(function ($nameQ) use ($searchTerm) {
                        $nameQ->where('customer_name', 'like', $searchTerm)
                              ->orWhere('customer_first_name', 'like', $searchTerm)
                              ->orWhere('customer_last_name', 'like', $searchTerm)
                              ->orWhereRaw("CONCAT(COALESCE(customer_first_name, ''), ' ', COALESCE(customer_last_name, '')) LIKE ?", [$searchTerm]);
                    });
                }
            });
        } else {
            // Single field searches - use AND logic
        // Filter by customer phone
            if ($hasPhoneSearch) {
                $phoneSearch = $request->customer_phone;
                $normalizedPhone = preg_replace('/[^\d+]/', '', $phoneSearch);
                
                $query->where(function ($q) use ($phoneSearch, $normalizedPhone) {
                    // Search with original format (in case user matches exact format)
                    $q->where('customer_phone', 'like', '%' . $phoneSearch . '%');
                    
                    // Also search with normalized format (remove spaces, dashes, etc.)
                    // This handles partial matches like "956" in "+961 3 956 879"
                    if (strlen($normalizedPhone) > 0) {
                        if ($normalizedPhone !== $phoneSearch) {
                            $q->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(customer_phone, ' ', ''), '-', ''), '(', ''), ')', ''), '+', '') LIKE ?", ['%' . $normalizedPhone . '%']);
                        }
                        // Also search the normalized version directly (handles partial matches)
                        $q->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(customer_phone, ' ', ''), '-', ''), '(', ''), ')', ''), '+', '') LIKE ?", ['%' . $phoneSearch . '%']);
                    }
                });
        }

            // Filter by customer name (search in customer_name, customer_first_name, customer_last_name)
            if ($hasNameSearch) {
                $searchTerm = '%' . $request->customer_name . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('customer_name', 'like', $searchTerm)
                      ->orWhere('customer_first_name', 'like', $searchTerm)
                      ->orWhere('customer_last_name', 'like', $searchTerm)
                      ->orWhereRaw("CONCAT(COALESCE(customer_first_name, ''), ' ', COALESCE(customer_last_name, '')) LIKE ?", [$searchTerm]);
                });
            }

            // Filter by reference (order ID or reference)
            if ($hasReferenceSearch) {
                $searchTerm = '%' . $request->reference . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('reference', 'like', $searchTerm)
                      ->orWhere('id', 'like', $searchTerm);
                });
            }
        }

        // Filter by pickup in store
        if ($request->has('pick_up_in_store')) {
            $query->where('pick_up_in_store', $request->boolean('pick_up_in_store'));
        }

        // Filter by date range
        if ($request->has('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->has('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        $orders = $query->latest()->paginate($request->get('per_page', 20));
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_first_name' => ['nullable', 'string', 'max:255'],
            'customer_last_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_address' => ['nullable', 'string'],
            'customer_city' => ['nullable', 'string', 'max:255'],
            'pick_up_in_store' => ['nullable', 'boolean'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'blind_width_cm' => ['nullable', 'numeric', 'min:0'],
            'blind_height_cm' => ['nullable', 'numeric', 'min:0'],
            'blind_image_path' => ['nullable', 'string'],
            'calc_multiplier' => ['nullable', 'integer', 'in:10,11'],
            'extra_charge' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $order = new Order($validated);
        $order->reference = 'ORD-' . strtoupper(uniqid());
        $order->save();

        $order->load(['lines', 'blinds']);
        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['lines', 'blinds'])->findOrFail($id);
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_first_name' => ['nullable', 'string', 'max:255'],
            'customer_last_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_address' => ['nullable', 'string'],
            'customer_city' => ['nullable', 'string', 'max:255'],
            'pick_up_in_store' => ['nullable', 'boolean'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'blind_width_cm' => ['nullable', 'numeric', 'min:0'],
            'blind_height_cm' => ['nullable', 'numeric', 'min:0'],
            'blind_image_path' => ['nullable', 'string'],
            'calc_multiplier' => ['nullable', 'integer', 'in:10,11'],
            'extra_charge' => ['nullable', 'numeric', 'min:0'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
        ]);
        
        $order->fill($validated)->save();
        $order->load(['lines', 'blinds']);
        
        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Check if a customer already exists based on phone or name
     */
    public function checkCustomer(Request $request)
    {
        $phone = $request->input('phone');
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $customerName = $request->input('customerName');
        $orderId = $request->input('orderId');

        // Normalize phone number for comparison
        $normalizePhone = function(?string $phone): ?string {
            if (empty($phone)) {
                return null;
            }
            // Remove all non-digit characters except +
            return preg_replace('/[^\d+]/', '', $phone) ?: null;
        };

        $existingOrders = collect();
        $message = '';
        $exists = false;

        // If phone is provided, check by phone (primary identifier)
        if (!empty($phone)) {
            $normalizedPhone = $normalizePhone($phone);

            // Get all orders and filter in memory for normalized phone matching
            $allOrders = Order::when($orderId, function ($query) use ($orderId) {
                    $query->where('id', '!=', $orderId);
                })
                ->whereNotNull('customer_phone')
                ->where('customer_phone', '!=', '')
                ->get();

            $existingOrders = $allOrders->filter(function ($order) use ($normalizedPhone, $normalizePhone) {
                $orderPhone = $normalizePhone($order->customer_phone);
                return $orderPhone && $orderPhone === $normalizedPhone;
            });

            if ($existingOrders->isNotEmpty()) {
                $count = $existingOrders->count();
                $exists = true;
                $ordersList = $existingOrders->map(function ($o) {
                    return [
                        'id' => $o->id,
                        'reference' => $o->reference,
                        'created_at' => $o->created_at->format('Y-m-d'),
                    ];
                })->values();
                $message = "A customer with phone number '{$phone}' already exists with {$count} existing order(s).";
                return response()->json([
                    'exists' => $exists,
                    'message' => $message,
                    'orders' => $ordersList,
                ]);
            }
        }

        // If no phone, check by name
        $composedName = trim(($firstName ?? '') . ' ' . ($lastName ?? '')) ?: $customerName;
        if (!empty($composedName)) {
            $existingOrders = Order::when($orderId, function ($query) use ($orderId) {
                    $query->where('id', '!=', $orderId);
                })
                ->where(function ($query) use ($composedName, $firstName, $lastName, $customerName) {
                    $query->where('customer_name', $composedName)
                        ->orWhere(function ($q) use ($firstName, $lastName) {
                            $q->where('customer_first_name', $firstName)
                              ->where('customer_last_name', $lastName);
                        });
                    if ($customerName) {
                        $query->orWhere('customer_name', $customerName);
                    }
                })
                ->get();

            if ($existingOrders->isNotEmpty()) {
                $count = $existingOrders->count();
                $exists = true;
                $ordersList = $existingOrders->map(function ($o) {
                    return [
                        'id' => $o->id,
                        'reference' => $o->reference,
                        'created_at' => $o->created_at->format('Y-m-d'),
                    ];
                })->values();
                $message = "A customer with name '{$composedName}' already exists with {$count} existing order(s).";
                return response()->json([
                    'exists' => $exists,
                    'message' => $message,
                    'orders' => $ordersList,
                ]);
            }
        }

        return response()->json([
            'exists' => false,
            'message' => '',
            'orders' => [],
        ]);
    }

    /**
     * Get reports statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reports()
    {
        // Get all orders with their blinds, excluding draft orders
        $orders = Order::with('blinds')
            ->where('status', '!=', 'draft')
            ->get();
        
        $totalBlinds = 0;
        $blindsByMultiplier10 = 0;
        $blindsByMultiplier11 = 0;
        $subtotal = 0;
        $grandTotal = 0;
        $profit = 0;
        $pickUpInStoreCount = 0;
        $ordersByCity = [];
        
        foreach ($orders as $order) {
            // Skip draft orders (additional safety check)
            if ($order->status === 'draft') {
                continue;
            }
            
            // Count pick up in store orders
            if ($order->pick_up_in_store) {
                $pickUpInStoreCount++;
            }
            
            // Count orders by city
            $city = $order->customer_city ?? 'No City';
            if (!isset($ordersByCity[$city])) {
                $ordersByCity[$city] = 0;
            }
            $ordersByCity[$city]++;
            
            foreach ($order->blinds as $blind) {
                $totalBlinds += $blind->qty ?? 1;
                
                if ($blind->calc_multiplier == 10) {
                    $blindsByMultiplier10 += $blind->qty ?? 1;
                } elseif ($blind->calc_multiplier == 11) {
                    $blindsByMultiplier11 += $blind->qty ?? 1;
                }
                
                // Add blind total to subtotal
                $subtotal += $blind->total_price ?? 0;
                
                // Calculate profit: extra * qty
                $profit += ($blind->extra_charge ?? 0) * ($blind->qty ?? 1);
            }
            
            // Add shipping cost to grand total
            $grandTotal += $order->shipping_cost ?? 0;
        }
        
        // Grand total = subtotal + shipping costs
        $grandTotal += $subtotal;
        
        // Sort cities by order count (descending) and convert to array format
        arsort($ordersByCity);
        $ordersByCityArray = [];
        foreach ($ordersByCity as $city => $count) {
            $ordersByCityArray[] = [
                'city' => $city,
                'count' => $count,
            ];
        }
        
        return response()->json([
            'total_blinds' => $totalBlinds,
            'blinds_multiplier_10' => $blindsByMultiplier10,
            'blinds_multiplier_11' => $blindsByMultiplier11,
            'subtotal' => round($subtotal, 2),
            'grand_total' => round($grandTotal, 2),
            'profit' => round($profit, 2),
            'pick_up_in_store_count' => $pickUpInStoreCount,
            'orders_by_city' => $ordersByCityArray,
        ]);
    }
}
