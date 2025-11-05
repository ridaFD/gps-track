<?php

namespace App\Orchid\Screens;

use App\Models\Order;
use App\Orchid\Layouts\CustomersTable;
use Orchid\Screen\Screen;
use Orchid\Screen\Layouts\Layout;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class CustomersScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        // Get unique customers grouped by phone number (primary identifier)
        // For customers without phone, we'll group by name
        // Using MAX() for all non-grouped columns to comply with ONLY_FULL_GROUP_BY
        $groupByExpr = 'COALESCE(NULLIF(customer_phone, \'\'), CONCAT(\'name_\', MD5(COALESCE(customer_name, CONCAT(COALESCE(customer_first_name, \'\'), \' \', COALESCE(customer_last_name, \'\'))))))';
        
        // First, get ALL customers to assign sequential IDs (for stable ID assignment)
        $allCustomersRaw = Order::selectRaw(
            $groupByExpr . ' as customer_key, ' .
            'MAX(customer_phone) as customer_phone, ' .
            'MAX(customer_name) as customer_name, ' .
            'MAX(customer_first_name) as customer_first_name, ' .
            'MAX(customer_last_name) as customer_last_name, ' .
            'MAX(customer_address) as customer_address, ' .
            'MAX(customer_city) as customer_city, ' .
            'COUNT(*) as total_orders, ' .
            'MAX(created_at) as last_order_date, ' .
            'MAX(id) as latest_order_id'
        )
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('customer_phone')
                      ->where('customer_phone', '!=', '');
                })
                ->orWhere(function ($q) {
                    $q->where(function ($q2) {
                        $q2->whereNull('customer_phone')
                           ->orWhere('customer_phone', '');
                    })
                    ->whereNotNull('customer_name')
                    ->where('customer_name', '!=', '');
                });
            })
            ->groupByRaw($groupByExpr)
            ->orderBy('last_order_date', 'desc')
            ->get();
        
        // Assign sequential IDs starting from 1
        $customerIdMap = []; // Maps customer_key to sequential ID
        $sequentialId = 1;
        foreach ($allCustomersRaw as $customer) {
            $customerIdMap[$customer->customer_key] = $sequentialId++;
        }
        
        // Now paginate the results
        $currentPage = request()->get('page', 1);
        $perPage = 20;
        $offset = ($currentPage - 1) * $perPage;
        $paginatedCustomers = $allCustomersRaw->slice($offset, $perPage);
        $totalCustomers = $allCustomersRaw->count();

        // Format the customers data with sequential IDs (1, 2, 3, ...)
        $formattedCustomers = $paginatedCustomers->map(function ($customer) use ($customerIdMap) {
            $name = trim($customer->customer_name ?? '');
            if (empty($name)) {
                $name = trim(($customer->customer_first_name ?? '') . ' ' . ($customer->customer_last_name ?? ''));
            }
            
            // Get sequential ID from map
            $sequentialId = $customerIdMap[$customer->customer_key] ?? 1;
            
            return (object) [
                'id' => $sequentialId,
                'customer_key' => $customer->customer_key, // Keep original key for querying
                'name' => $name ?: 'N/A',
                'phone' => !empty($customer->customer_phone) ? $customer->customer_phone : 'N/A',
                'address' => $customer->customer_address ?: 'N/A',
                'city' => $customer->customer_city ?: 'N/A',
                'total_orders' => $customer->total_orders ?? 0,
                'last_order_date' => $customer->last_order_date,
                'latest_order_id' => $customer->latest_order_id,
            ];
        });

        return [
            'customers' => new \Illuminate\Pagination\LengthAwarePaginator(
                $formattedCustomers,
                $totalCustomers,
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            ),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Customers';
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            CustomersTable::class,
        ];
    }

    /**
     * Delete a customer and all associated orders.
     *
     * @param Request $request
     * @return void
     */
    public function remove(Request $request): void
    {
        \Log::info('[DEBUG] CustomersScreen remove method CALLED', [
            'request_all' => $request->all(),
            'request_method' => $request->method(),
            'request_url' => $request->fullUrl(),
            'headers' => $request->headers->all()
        ]);
        
        // Get customer ID from request parameters (try multiple ways)
        $customer = $request->input('customer') ?? $request->get('customer') ?? $request->customer ?? null;
        
        \Log::info('[DEBUG] CustomersScreen delete - customer ID', [
            'customer_id_from_input' => $request->input('customer'),
            'customer_id_from_get' => $request->get('customer'),
            'customer_id_final' => $customer,
        ]);
        
        if (!$customer) {
            \Log::error('[DEBUG] CustomersScreen delete - No customer ID provided', [
                'request_all' => $request->all()
            ]);
            Toast::error('Customer ID is required');
            return;
        }
        
        // Get customer key (same logic as query method - MUST match exactly for sequential IDs to align)
        $groupByExpr = 'COALESCE(NULLIF(customer_phone, \'\'), CONCAT(\'name_\', MD5(COALESCE(customer_name, CONCAT(COALESCE(customer_first_name, \'\'), \' \', COALESCE(customer_last_name, \'\'))))))';
        
        $allCustomers = Order::selectRaw(
            $groupByExpr . ' as customer_key, ' .
            'MAX(customer_phone) as customer_phone, ' .
            'MAX(customer_name) as customer_name, ' .
            'MAX(customer_first_name) as customer_first_name, ' .
            'MAX(customer_last_name) as customer_last_name, ' .
            'MAX(created_at) as last_order_date'
        )
        ->where(function ($query) {
            $query->where(function ($q) {
                $q->whereNotNull('customer_phone')
                  ->where('customer_phone', '!=', '');
            })
            ->orWhere(function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNull('customer_phone')
                       ->orWhere('customer_phone', '');
                })
                ->whereNotNull('customer_name')
                ->where('customer_name', '!=', '');
            });
        })
        ->groupByRaw($groupByExpr)
        ->orderBy('last_order_date', 'desc') // MUST match query() method ordering
        ->get();
        
        // Assign sequential IDs the same way as in query method
        $customerIdMap = [];
        $sequentialId = 1;
        foreach ($allCustomers as $cust) {
            $customerIdMap[$sequentialId] = $cust->customer_key;
            $sequentialId++;
        }
        
        \Log::info('[DEBUG] CustomersScreen delete - customer mapping', [
            'customer_id' => $customer,
            'customer_id_in_map' => isset($customerIdMap[$customer]),
            'total_customers' => count($customerIdMap)
        ]);
        
        // Find the customer by sequential ID
        if (!isset($customerIdMap[$customer])) {
            Toast::error('Customer not found');
            return;
        }
        
        $customerKey = $customerIdMap[$customer];
        $isPhoneBased = !str_starts_with($customerKey, 'name_');
        
        \Log::info('[DEBUG] CustomersScreen delete - customer details', [
            'customer_key' => $customerKey,
            'is_phone_based' => $isPhoneBased
        ]);
        
        // Get all orders for this customer
        if ($isPhoneBased) {
            // Normalize the customer key (phone) for matching
            $normalizedCustomerKey = preg_replace('/[^\d+]/', '', $customerKey);
            
            \Log::info('[DEBUG] CustomersScreen delete - phone-based customer', [
                'normalized_customer_key' => $normalizedCustomerKey
            ]);
            
            // Get all orders and filter in memory for normalized phone matching
            $allOrders = Order::whereNotNull('customer_phone')
                ->where('customer_phone', '!=', '')
                ->get();
            
            $orders = $allOrders->filter(function ($order) use ($normalizedCustomerKey) {
                $orderPhone = preg_replace('/[^\d+]/', '', $order->customer_phone ?? '');
                return $orderPhone && $orderPhone === $normalizedCustomerKey;
            });
        } else {
            $allOrders = Order::where(function ($query) {
                $query->whereNull('customer_phone')
                    ->orWhere('customer_phone', '');
            })
            ->whereNotNull('customer_name')
            ->where('customer_name', '!=', '')
            ->get();
            
            $orders = $allOrders->filter(function ($order) use ($customerKey) {
                $name = trim($order->customer_name ?? '');
                if (empty($name)) {
                    $name = trim(($order->customer_first_name ?? '') . ' ' . ($order->customer_last_name ?? ''));
                }
                $nameHash = 'name_' . md5($name);
                return $nameHash === $customerKey;
            });
        }
        
        \Log::info('[DEBUG] CustomersScreen delete - orders found', [
            'customer_key' => $customerKey,
            'is_phone_based' => $isPhoneBased,
            'orders_count' => $orders->count(),
            'order_ids' => $orders->pluck('id')->toArray()
        ]);
        
        // Remove customer information from all orders (but keep the orders)
        $updatedCount = 0;
        foreach ($orders as $order) {
            // Clear all customer-related fields but keep the order
            $order->customer_name = null;
            $order->customer_first_name = null;
            $order->customer_last_name = null;
            $order->customer_phone = null;
            $order->customer_address = null;
            $order->customer_city = null;
            $order->save();
            $updatedCount++;
            
            \Log::info('[DEBUG] CustomersScreen delete - customer info cleared from order', [
                'order_id' => $order->id
            ]);
        }
        
        \Log::info('[DEBUG] CustomersScreen delete - completed', [
            'orders_updated' => $updatedCount
        ]);
        
        if ($updatedCount > 0) {
            Toast::success("Customer deleted successfully. {$updatedCount} order(s) kept but customer information removed.");
        } else {
            Toast::warning("Customer found but no orders to update.");
        }
    }
}

