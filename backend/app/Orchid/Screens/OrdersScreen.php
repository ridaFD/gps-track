<?php

namespace App\Orchid\Screens;

use App\Models\Order;
use App\Orchid\Layouts\OrdersTable;
use App\Orchid\Layouts\OrdersFiltersLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Layouts\Layout;
use Orchid\Screen\Actions\Link;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class OrdersScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'orders' => Order::with('blinds')
                ->filters(OrdersFiltersLayout::class)
                ->latest()
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Orders';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Create Order')
                ->icon('bs.plus')
                ->route('platform.orders.create'),
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
            OrdersFiltersLayout::class,
            OrdersTable::class,
        ];
    }

    /**
     * Delete an order.
     *
     * @param Request $request
     * @return void
     */
    public function remove(Request $request): void
    {
        $orderId = $request->input('order');
        
        if (!$orderId) {
            Toast::error('Order ID is required');
            return;
        }
        
        $order = Order::find($orderId);
        
        if (!$order) {
            Toast::error('Order not found');
            return;
        }
        
        // Delete related data first
        $order->blinds()->delete();
        $order->lines()->delete();
        
        // Delete the order
        $order->delete();
        
        Toast::success('Order deleted successfully');
    }
}
