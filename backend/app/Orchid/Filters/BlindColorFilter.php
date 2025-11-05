<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Input;

class BlindColorFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Color');
    }

    /**
     * The array of matched parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return ['color'];
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
        return $builder->where('color', 'like', '%'.$this->request->get('color').'%');
    }

    /**
     * Get the display fields.
     */
    public function display(): array
    {
        return [
            Input::make('color')
                ->type('text')
                ->value($this->request->get('color'))
                ->title(__('Color'))
                ->placeholder(__('Search by color name...')),
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        return $this->name().': '.$this->request->get('color');
    }
}

