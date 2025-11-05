<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Input;

class ReferenceFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Reference');
    }

    /**
     * The array of matched parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return ['reference'];
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
        $value = $this->request->get('reference');
        
        if ($value) {
            return $builder->where('reference', 'like', "%{$value}%");
        }
        
        return $builder;
    }

    /**
     * Get the display fields.
     */
    public function display(): array
    {
        return [
            Input::make('reference')
                ->title(__('Reference'))
                ->value($this->request->get('reference'))
                ->placeholder(__('Search by reference...')),
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        $value = $this->request->get('reference');
        if ($value) {
            return $this->name() . ': ' . $value;
        }
        return '';
    }
}

