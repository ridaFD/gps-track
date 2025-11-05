<?php

namespace App\Orchid\Layouts;

use App\Models\Order;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

class CustomerOrdersTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'orders';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('reference', 'Order Reference')
                ->render(function (Order $order) {
                    $reference = $order->reference ?? ('Order #' . $order->id);
                    return Link::make($reference)
                        ->route('platform.orders.edit', $order->id);
                })
                ->sort(),

            TD::make('status', 'Status')
                ->render(fn (Order $order) => e($order->status ?? 'N/A'))
                ->sort(),

            TD::make('pick_up_in_store', 'Delivery Type')
                ->render(function (Order $order) {
                    if ($order->pick_up_in_store) {
                        return '<span class="badge bg-info"><i class="bi bi-shop"></i> Pick up in Store</span>';
                    } else {
                        return '<span class="badge bg-primary"><i class="bi bi-truck"></i> Delivery</span>';
                    }
                })
                ->sort(),

            TD::make('total_amount', 'Total Amount')
                ->render(function (Order $order) {
                    // Calculate total from blinds if total_amount is not set or zero
                    $amount = $order->total_amount ?? 0;
                    
                    if ($amount <= 0) {
                        // Load blinds relationship if not already loaded
                        if (!$order->relationLoaded('blinds')) {
                            $order->load('blinds');
                        }
                        
                        // Sum all blinds' total_price
                        $blindsTotal = $order->blinds->sum('total_price') ?? 0;
                        
                        // Add shipping cost
                        $shippingCost = $order->shipping_cost ?? 0;
                        $amount = $blindsTotal + $shippingCost;
                    }
                    
                    if ($amount > 0) {
                        return number_format($amount, 2) . ' USD';
                    }
                    return 'N/A';
                })
                ->sort(),

            TD::make('created_at', 'Order Date')
                ->render(fn (Order $order) => optional($order->created_at)->format('Y-m-d H:i') ?? 'N/A')
                ->sort(),

            TD::make('actions', 'Actions')
                ->render(function (Order $order) {
                    return Link::make('View Order')
                        ->icon('bs.eye')
                        ->route('platform.orders.edit', $order->id);
                }),
        ];
    }
}

