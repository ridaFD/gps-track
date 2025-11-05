<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Input;

class CustomerPhoneFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Customer Phone');
    }

    /**
     * The array of matched parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return ['customer_phone'];
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
        $value = $this->request->get('customer_phone');
        
        if ($value) {
            return $builder->where('customer_phone', 'like', "%{$value}%");
        }
        
        return $builder;
    }

    /**
     * Get the display fields.
     */
    public function display(): array
    {
        return [
            Input::make('customer_phone')
                ->title(__('Customer Phone'))
                ->value($this->request->get('customer_phone'))
                ->placeholder(__('Search by phone number...')),
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        $value = $this->request->get('customer_phone');
        if ($value) {
            return $this->name() . ': ' . $value;
        }
        return '';
    }
}

