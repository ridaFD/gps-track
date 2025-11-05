<?php

namespace App\Orchid\Layouts;

use App\Models\Order;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class OrdersTable extends Table
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
            TD::make('reference', 'Reference')
                ->render(fn (Order $order) => Link::make($order->reference ?? ('Order #' . $order->id))
                    ->route('platform.orders.edit', $order->id)),
            TD::make('customer_name', 'Customer')
                ->render(fn (Order $order) => e($order->customer_name ?? trim(($order->customer_first_name ?? '') . ' ' . ($order->customer_last_name ?? '')))),
            TD::make('customer_phone', 'Phone')
                ->render(fn (Order $order) => e((string) $order->customer_phone)),
            TD::make('pick_up_in_store', 'Delivery Type')
                ->render(function (Order $order) {
                    if ($order->pick_up_in_store) {
                        return '<span class="badge bg-info"><i class="bi bi-shop"></i> Pick up in Store</span>';
                    } else {
                        return '<span class="badge bg-primary"><i class="bi bi-truck"></i> Delivery</span>';
                    }
                })
                ->sort(),
            TD::make('status', 'Status')
                ->render(fn (Order $order) => e((string) $order->status)),
            
            TD::make('stock_alert', 'Stock Alert')
                ->render(function (Order $order) {
                    if ($order->relationLoaded('blinds')) {
                        $hasAlert = $order->blinds->contains('stock_alert', true);
                    } else {
                        $hasAlert = $order->blinds()->where('stock_alert', true)->exists();
                    }
                    
                    if ($hasAlert) {
                        return '<span class="badge bg-danger">⚠️ Out of Stock</span>';
                    }
                    return '<span class="text-muted">✓ OK</span>';
                }),
            
            TD::make('created_at', 'Created')
                ->render(fn (Order $order) => optional($order->created_at)->toDateTimeString())
                ->sort(),
                
            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Order $order) {
                    // Load order blinds for the modal
                    $order->load('blinds');
                    
                    // Render the full view
                    $fullHtml = view('orchid.order-send-button', ['order' => $order])->render();
                    
                    // Find the modal start (<!-- Modal -->) and extract everything from there
                    $modalStartPos = strpos($fullHtml, '<!-- Modal -->');
                    if ($modalStartPos !== false) {
                        // Extract everything from modal start to end
                        $modalHtml = substr($fullHtml, $modalStartPos);
                    } else {
                        // Fallback: remove button using regex
                        $modalHtml = preg_replace('/<button[^>]*>[\s\S]*?Send[\s\S]*?<\/button>\s*/i', '', $fullHtml);
                    }
                    
                    // Create dropdown with Send and Delete options
                    $dropdown = DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Button::make(__('Send'))
                                ->icon('bs.send')
                                ->rawClick()
                                ->action('javascript:openSendModal(' . $order->id . ');'),
                            Button::make(__('Delete'))
                                ->icon('bs.trash')
                                ->method('remove', [
                                    'order' => $order->id,
                                ])
                                ->confirm(__('Are you sure you want to delete this order? This action cannot be undone.'))
                                ->type(Color::DANGER),
                        ]);
                    
                    // Return dropdown and modal HTML (without the duplicate Send button)
                    return (string) $dropdown . $modalHtml;
                }),
        ];
    }
}
