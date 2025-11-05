<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Support\Color;

class CustomersTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'customers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Customer Name')
                ->render(function ($customer) {
                    $name = e($customer->name ?? 'N/A');
                    // Link to customer edit screen using customer key (id)
                    return Link::make($name)
                        ->route('platform.customers.edit', $customer->id ?? '');
                })
                ->sort(),

            TD::make('phone', 'Phone')
                ->render(fn ($customer) => e($customer->phone ?? 'N/A')),

            TD::make('address', 'Address')
                ->render(fn ($customer) => e($customer->address ?? 'N/A')),

            TD::make('city', 'City')
                ->render(fn ($customer) => e($customer->city ?? 'N/A')),

            TD::make('total_orders', 'Total Orders')
                ->render(function ($customer) {
                    $count = $customer->total_orders ?? 0;
                    if ($count > 0 && isset($customer->phone) && $customer->phone !== 'N/A') {
                        return Link::make($count)
                            ->route('platform.orders')
                            ->parameters(['filter' => ['customer_phone' => $customer->phone]]);
                    }
                    return $count;
                })
                ->sort(),

            TD::make('last_order_date', 'Last Order')
                ->render(fn ($customer) => optional($customer->last_order_date)->format('Y-m-d H:i') ?? 'N/A')
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function ($customer) {
                    if (!isset($customer->id)) {
                        return '';
                    }
                    
                    return Button::make(__('Delete'))
                        ->icon('bs.trash')
                        ->method('remove', [
                            'customer' => $customer->id,
                        ])
                        ->confirm(__('Are you sure you want to delete this customer? This will remove customer information from all associated orders, but keep the orders. This action cannot be undone.'))
                        ->type(Color::DANGER);
                }),
        ];
    }
}

