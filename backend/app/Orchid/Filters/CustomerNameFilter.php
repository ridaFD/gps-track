<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Input;

class CustomerNameFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Customer Name');
    }

    /**
     * The array of matched parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return ['customer_name'];
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
        $value = $this->request->get('customer_name');
        
        if ($value) {
            return $builder->where(function (Builder $query) use ($value) {
                $query->where('customer_name', 'like', "%{$value}%")
                      ->orWhere('customer_first_name', 'like', "%{$value}%")
                      ->orWhere('customer_last_name', 'like', "%{$value}%");
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
            Input::make('customer_name')
                ->title(__('Customer Name'))
                ->value($this->request->get('customer_name'))
                ->placeholder(__('Search by customer name...')),
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        $value = $this->request->get('customer_name');
        if ($value) {
            return $this->name() . ': ' . $value;
        }
        return '';
    }
}

