<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Select;

class BlindActiveFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Active');
    }

    /**
     * The array of matched parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return ['is_active'];
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
        $value = $this->request->get('is_active');
        
        if ($value === '1' || $value === 'true') {
            return $builder->where('is_active', true);
        }
        
        if ($value === '0' || $value === 'false') {
            return $builder->where('is_active', false);
        }
        
        return $builder;
    }

    /**
     * Get the display fields.
     */
    public function display(): array
    {
        return [
            Select::make('is_active')
                ->options([
                    '' => __('All'),
                    '1' => __('Active'),
                    '0' => __('Inactive'),
                ])
                ->value($this->request->get('is_active'))
                ->title(__('Status')),
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        $value = $this->request->get('is_active');
        if ($value === '1') {
            return $this->name().': '.__('Active');
        }
        if ($value === '0') {
            return $this->name().': '.__('Inactive');
        }
        return '';
    }
}

