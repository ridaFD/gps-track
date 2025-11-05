<?php

namespace App\Orchid\Screens;

use App\Models\Order;
use App\Orchid\Layouts\CustomerOrdersTable;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\Request;

class CustomerEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(int $customer): iterable
    {
        // The customer parameter is now a sequential numeric ID (1, 2, 3, ...)
        // We need to find the original customer_key by matching the sequential ID
        $groupByExpr = 'COALESCE(NULLIF(customer_phone, \'\'), CONCAT(\'name_\', MD5(COALESCE(customer_name, CONCAT(COALESCE(customer_first_name, \'\'), \' \', COALESCE(customer_last_name, \'\'))))))';
        
        $allCustomers = Order::selectRaw(
            $groupByExpr . ' as customer_key, ' .
            'MAX(customer_phone) as customer_phone, ' .
            'MAX(customer_name) as customer_name, ' .
            'MAX(customer_first_name) as customer_first_name, ' .
            'MAX(customer_last_name) as customer_last_name, ' .
            'MAX(customer_address) as customer_address, ' .
            'MAX(customer_city) as customer_city, ' .
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
        ->orderBy('last_order_date', 'desc')
        ->get();
        
        // Assign sequential IDs the same way as in CustomersScreen
        $customerIdMap = [];
        $sequentialId = 1;
        foreach ($allCustomers as $cust) {
            $customerIdMap[$sequentialId] = $cust->customer_key;
            $sequentialId++;
        }
        
        // Find the customer by sequential ID
        if (!isset($customerIdMap[$customer])) {
            Alert::error('Customer not found');
            return ['customer' => [], 'orders' => collect(), 'total_orders' => 0];
        }
        
        $customerKey = $customerIdMap[$customer];
        
        // Get the customer info from the aggregated query result
        $foundCustomer = $allCustomers->firstWhere('customer_key', $customerKey);
        
        // Determine if this is a phone-based or name-based customer
        $isPhoneBased = !str_starts_with($customerKey, 'name_');
        
        if ($isPhoneBased) {
            // Customer identified by phone number
            $orders = Order::with('blinds')
                ->where('customer_phone', $customerKey)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Customer identified by name hash
            // Get all orders without phone that match this name hash
            $allOrders = Order::with('blinds')
                ->where(function ($query) {
                    $query->whereNull('customer_phone')
                        ->orWhere('customer_phone', '');
                })
            ->whereNotNull('customer_name')
            ->where('customer_name', '!=', '')
            ->orderBy('created_at', 'desc')
            ->get();
            
            // Filter orders where the name hash matches
            $orders = $allOrders->filter(function ($order) use ($customerKey) {
                $name = trim($order->customer_name ?? '');
                if (empty($name)) {
                    $name = trim(($order->customer_first_name ?? '') . ' ' . ($order->customer_last_name ?? ''));
                }
                $nameHash = 'name_' . md5($name);
                return $nameHash === $customerKey;
            })->values();
        }
        
        // Use the aggregated customer data from the query (which uses MAX(customer_city))
        // This ensures consistency with how we group customers
        $customerData = [
            'id' => $customer,
            'key' => $customerKey,
            'phone' => $foundCustomer ? ($foundCustomer->customer_phone ?? '') : ($isPhoneBased ? $customerKey : ''),
            'name' => $foundCustomer ? ($foundCustomer->customer_name ?? '') : '',
            'first_name' => $foundCustomer ? ($foundCustomer->customer_first_name ?? '') : '',
            'last_name' => $foundCustomer ? ($foundCustomer->customer_last_name ?? '') : '',
            'address' => $foundCustomer ? ($foundCustomer->customer_address ?? '') : '',
            'city' => $foundCustomer ? ($foundCustomer->customer_city ?? '') : '',
        ];
        
        return [
            'customer' => $customerData,
            'orders' => $orders,
            'total_orders' => $orders->count(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Customer Details';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'View and edit customer information and order history';
    }

    /**
     * Get cities options from JSON file
     *
     * @return array
     */
    protected function getCitiesOptions(): array
    {
        $options = [];
        
        // Add empty option as default
        $options[''] = 'Please select a city';
        
        $citiesFile = base_path('cities.json');
        if (file_exists($citiesFile)) {
            $cities = json_decode(file_get_contents($citiesFile), true);
            if (is_array($cities)) {
                // Add cities as key => value pairs
                foreach ($cities as $city) {
                    $options[$city] = $city;
                }
            }
        }
        
        return $options;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save Changes')
                ->icon('bs.save')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('customer.name')
                    ->title('Customer Name')
                    ->placeholder('Full name'),
                
                Input::make('customer.first_name')
                    ->title('First Name')
                    ->placeholder('First name'),
                
                Input::make('customer.last_name')
                    ->title('Last Name')
                    ->placeholder('Last name'),
                
                Input::make('customer.phone')
                    ->type('tel')
                    ->title('Phone Number')
                    ->placeholder('Phone number')
                    ->help('Enter customer phone number with country code'),
                
                TextArea::make('customer.address')
                    ->title('Address')
                    ->placeholder('Customer address'),
                
                Select::make('customer.city')
                    ->title('Customer City')
                    ->options($this->getCitiesOptions())
                    ->searchable()
                    ->placeholder('Please select a city')
                    ->empty('Please select a city'),
                
                Input::make('total_orders')
                    ->title('Total Orders')
                    ->readonly(),
            ])->title('Customer Information'),
            
            Layout::view('orchid.customer-check-js'),
            
            CustomerOrdersTable::class,
        ];
    }

    /**
     * Save customer information.
     */
    public function save(Request $request, int $customer)
    {
        // Get customer key (similar to query method)
        $groupByExpr = 'COALESCE(NULLIF(customer_phone, \'\'), CONCAT(\'name_\', MD5(COALESCE(customer_name, CONCAT(COALESCE(customer_first_name, \'\'), \' \', COALESCE(customer_last_name, \'\'))))))';
        
        $allCustomers = Order::selectRaw(
            $groupByExpr . ' as customer_key, ' .
            'MAX(customer_phone) as customer_phone'
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
        ->get();
        
        // Assign sequential IDs the same way as in CustomersScreen
        $customerIdMap = [];
        $sequentialId = 1;
        foreach ($allCustomers as $cust) {
            $customerIdMap[$sequentialId] = $cust->customer_key;
            $sequentialId++;
        }
        
        // Find the customer by sequential ID
        if (!isset($customerIdMap[$customer])) {
            Toast::error('Customer not found');
            return;
        }
        
        $customerKey = $customerIdMap[$customer];
        $isPhoneBased = !str_starts_with($customerKey, 'name_');
        
        // Get all orders for this customer BEFORE updating
        // Store order IDs to verify all are updated
        if ($isPhoneBased) {
            // Normalize the customer key (phone) for matching
            // Remove all non-digit characters except + to match the normalization in query method
            $normalizedCustomerKey = preg_replace('/[^\d+]/', '', $customerKey);
            
            \Log::info('[DEBUG] CustomerEditScreen save - finding orders', [
                'customer_key' => $customerKey,
                'normalized_customer_key' => $normalizedCustomerKey,
                'is_phone_based' => true
            ]);
            
            // Get all orders and filter in memory for normalized phone matching
            // This handles cases where phone numbers are stored in different formats
            $allOrders = Order::whereNotNull('customer_phone')
                ->where('customer_phone', '!=', '')
                ->get();
            
            $orders = $allOrders->filter(function ($order) use ($normalizedCustomerKey) {
                $orderPhone = preg_replace('/[^\d+]/', '', $order->customer_phone ?? '');
                $matches = $orderPhone && $orderPhone === $normalizedCustomerKey;
                if ($matches) {
                    \Log::info('[DEBUG] CustomerEditScreen save - order matched', [
                        'order_id' => $order->id,
                        'order_phone' => $order->customer_phone,
                        'normalized_order_phone' => $orderPhone
                    ]);
                }
                return $matches;
            });
            
            \Log::info('[DEBUG] CustomerEditScreen save - orders found', [
                'total_orders_in_db' => $allOrders->count(),
                'matched_orders_count' => $orders->count(),
                'order_ids' => $orders->pluck('id')->toArray()
            ]);
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
        
        // Get the new phone number from request and normalize it
        $newPhone = $request->input('customer.phone');
        \Log::info('[DEBUG] CustomerEditScreen save', [
            'raw_phone' => $newPhone,
            'orders_count' => $orders->count()
        ]);
        
        if (!empty($newPhone)) {
            // Remove all non-digit characters except + for consistent storage
            $newPhone = preg_replace('/[^\d+]/', '', $newPhone);
            // Ensure it starts with + if it doesn't already
            if (!empty($newPhone) && !str_starts_with($newPhone, '+')) {
                $newPhone = '+' . $newPhone;
            }
        }
        
        \Log::info('[DEBUG] CustomerEditScreen save - normalized phone', [
            'normalized_phone' => $newPhone
        ]);
        
        // Verify we have orders to update
        if ($orders->isEmpty()) {
            \Log::warning('[DEBUG] CustomerEditScreen save - No orders found to update', [
                'customer_id' => $customer,
                'customer_key' => $customerKey,
                'is_phone_based' => $isPhoneBased
            ]);
            Toast::warning('No orders found for this customer to update.');
            return redirect()->route('platform.customers.edit', $customer);
        }
        
        // Store order IDs before update for verification
        $orderIds = $orders->pluck('id')->toArray();
        
        // Update all orders with new customer information
        $updated = 0;
        foreach ($orders as $order) {
            $oldPhone = $order->customer_phone;
            
            $order->customer_name = $request->input('customer.name');
            $order->customer_first_name = $request->input('customer.first_name');
            $order->customer_last_name = $request->input('customer.last_name');
            $order->customer_phone = $newPhone ?: null;
            $order->customer_address = $request->input('customer.address');
            $order->customer_city = $request->input('customer.city');
            
            \Log::info('[DEBUG] CustomerEditScreen save - updating order', [
                'order_id' => $order->id,
                'customer_phone_before' => $oldPhone,
                'customer_phone_after' => $newPhone ?: null
            ]);
            
            $order->save();
            
            // Verify the update was successful
            $order->refresh();
            
            \Log::info('[DEBUG] CustomerEditScreen save - order saved', [
                'order_id' => $order->id,
                'customer_phone_after_save' => $order->customer_phone,
                'update_successful' => $order->customer_phone === $newPhone
            ]);
            
            $updated++;
        }
        
        // After updating, verify no orders remain with old phone
        if ($isPhoneBased && !empty($normalizedCustomerKey)) {
            $remainingOrdersWithOldPhone = Order::whereNotNull('customer_phone')
                ->where('customer_phone', '!=', '')
                ->whereIn('id', $orderIds)
                ->get()
                ->filter(function ($order) use ($normalizedCustomerKey) {
                    $orderPhone = preg_replace('/[^\d+]/', '', $order->customer_phone ?? '');
                    return $orderPhone && $orderPhone === $normalizedCustomerKey;
                });
            
            \Log::info('[DEBUG] CustomerEditScreen save - verification', [
                'orders_updated' => $updated,
                'order_ids_updated' => $orderIds,
                'remaining_orders_with_old_phone' => $remainingOrdersWithOldPhone->count(),
                'new_phone' => $newPhone
            ]);
            
            if ($remainingOrdersWithOldPhone->isNotEmpty()) {
                \Log::error('[DEBUG] CustomerEditScreen save - Some orders still have old phone!', [
                    'remaining_order_ids' => $remainingOrdersWithOldPhone->pluck('id')->toArray()
                ]);
            }
        }
        
        Toast::success("Customer information updated successfully for {$updated} order(s).");
        
        // After updating, we need to find the customer's new sequential ID
        // because if the phone number changed, the customer key changed
        $newCustomerKey = null;
        if (!empty($newPhone)) {
            // Use the exact same normalization as in query method
            $newCustomerKey = preg_replace('/[^\d+]/', '', $newPhone);
        } else {
            // If phone was removed, use name-based key (same logic as query method)
            $name = trim($request->input('customer.name') ?? '');
            if (empty($name)) {
                $name = trim(($request->input('customer.first_name') ?? '') . ' ' . ($request->input('customer.last_name') ?? ''));
            }
            if (!empty($name)) {
                $newCustomerKey = 'name_' . md5($name);
            }
        }
        
        \Log::info('[DEBUG] CustomerEditScreen save - new customer key', [
            'new_customer_key' => $newCustomerKey
        ]);
        
        // Re-fetch all customers to find the new sequential ID (using same query as query method)
        $allCustomersAfterUpdate = Order::selectRaw(
            $groupByExpr . ' as customer_key'
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
        ->orderByRaw('MAX(created_at) DESC')
        ->get();
        
        // Find the new sequential ID
        $newSequentialId = null;
        $sequentialId = 1;
        foreach ($allCustomersAfterUpdate as $cust) {
            if ($cust->customer_key === $newCustomerKey) {
                $newSequentialId = $sequentialId;
                break;
            }
            $sequentialId++;
        }
        
        \Log::info('[DEBUG] CustomerEditScreen save - redirect', [
            'old_customer_id' => $customer,
            'new_customer_id' => $newSequentialId,
            'new_customer_key' => $newCustomerKey
        ]);
        
        // Redirect to the updated customer's page if sequential ID changed
        if ($newSequentialId && $newSequentialId != $customer) {
            return redirect()->route('platform.customers.edit', $newSequentialId);
        }
        
        // Otherwise, stay on the same page (refresh)
        return redirect()->route('platform.customers.edit', $customer);
    }
}

