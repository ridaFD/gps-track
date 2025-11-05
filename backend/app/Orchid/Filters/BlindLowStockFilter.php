<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Select;

class BlindLowStockFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Stock Level');
    }

    /**
     * The array of matched parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return ['stock_level'];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $value = $this->request->get('stock_level');
        
        if ($value === 'out_of_stock') {
            return $builder->where('stock_qty', 0);
        }
        
        if ($value === 'low_stock') {
            return $builder->whereRaw('stock_qty <= low_stock_threshold AND stock_qty > 0');
        }
        
        if ($value === 'in_stock') {
            return $builder->whereRaw('stock_qty > low_stock_threshold');
        }
        
        return $builder;
    }

    /**
     * Get the display fields.
     */
    public function display(): array
    {
        return [
            Select::make('stock_level')
                ->options([
                    '' => __('All'),
                    'in_stock' => __('✓ In Stock'),
                    'low_stock' => __('⚠️ Low Stock'),
                    'out_of_stock' => __('🚫 Out of Stock'),
                ])
                ->value($this->request->get('stock_level'))
                ->title(__('Stock Level')),
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        $value = $this->request->get('stock_level');
        $labels = [
            'in_stock' => __('In Stock'),
            'low_stock' => __('Low Stock'),
            'out_of_stock' => __('Out of Stock'),
        ];
        
        return isset($labels[$value]) ? $this->name().': '.$labels[$value] : '';
    }
}

