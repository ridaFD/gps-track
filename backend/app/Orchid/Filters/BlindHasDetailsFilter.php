<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Select;

class BlindHasDetailsFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Has Details');
    }

    /**
     * The array of matched parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return ['has_details'];
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
        $value = $this->request->get('has_details');
        
        if ($value === '1' || $value === 'true') {
            return $builder->where('has_details', true);
        }
        
        if ($value === '0' || $value === 'false') {
            return $builder->where('has_details', false);
        }
        
        return $builder;
    }

    /**
     * Get the display fields.
     */
    public function display(): array
    {
        return [
            Select::make('has_details')
                ->options([
                    '' => __('All'),
                    '1' => __('With Details'),
                    '0' => __('Without Details'),
                ])
                ->value($this->request->get('has_details'))
                ->title(__('Extra Detailing')),
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        $value = $this->request->get('has_details');
        if ($value === '1') {
            return $this->name().': '.__('With Details');
        }
        if ($value === '0') {
            return $this->name().': '.__('Without Details');
        }
        return '';
    }
}

