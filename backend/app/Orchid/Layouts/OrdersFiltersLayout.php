<?php

namespace App\Orchid\Layouts;

use App\Orchid\Filters\StockAlertFilter;
use App\Orchid\Filters\OrderStatusFilter;
use App\Orchid\Filters\CustomerNameFilter;
use App\Orchid\Filters\CustomerPhoneFilter;
use App\Orchid\Filters\ReferenceFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class OrdersFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            ReferenceFilter::class,
            CustomerNameFilter::class,
            CustomerPhoneFilter::class,
            OrderStatusFilter::class,
            StockAlertFilter::class,
        ];
    }
}

