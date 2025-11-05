<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Select;

class StockAlertFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Stock Alert');
    }

    /**
     * The array of matched parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return ['stock_alert'];
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
        $value = $this->request->get('stock_alert');
        
        if ($value === '1') {
            return $builder->whereHas('blinds', function (Builder $query) {
                $query->where('stock_alert', true);
            });
        }
        
        return $builder;
    }

    /**
     * Get the display fields.
     */
    public function display(): array
    {
        return [
            Select::make('stock_alert')
                ->options([
                    '' => 'All Orders',
                    '1' => 'Orders with Stock Alerts',
                ])
                ->value($this->request->get('stock_alert'))
                ->title(__('Filter by Stock Alert')),
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        $value = $this->request->get('stock_alert');
        if ($value === '1') {
            return $this->name() . ': Orders with Stock Alerts';
        }
        return '';
    }
}

