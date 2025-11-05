<?php

namespace App\Orchid\Layouts;

use App\Orchid\Filters\BlindColorFilter;
use App\Orchid\Filters\BlindHasDetailsFilter;
use App\Orchid\Filters\BlindActiveFilter;
use App\Orchid\Filters\BlindLowStockFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class BlindFiltersLayout extends Selection
{
    /**
     * @return string[]|Filter[]
     */
    public function filters(): array
    {
        return [
            BlindColorFilter::class,
            BlindHasDetailsFilter::class,
            BlindActiveFilter::class,
            BlindLowStockFilter::class,
        ];
    }
}

