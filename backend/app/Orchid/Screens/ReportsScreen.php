<?php

namespace App\Orchid\Screens;

use App\Models\Order;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ReportsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
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
        
        // Sort cities by order count (descending)
        arsort($ordersByCity);
        
        return [
            'total_blinds' => $totalBlinds,
            'blinds_multiplier_10' => $blindsByMultiplier10,
            'blinds_multiplier_11' => $blindsByMultiplier11,
            'subtotal' => $subtotal,
            'grand_total' => $grandTotal,
            'profit' => $profit,
            'pick_up_in_store_count' => $pickUpInStoreCount,
            'orders_by_city' => $ordersByCity,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Reports';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('orchid.reports'),
        ];
    }
}
