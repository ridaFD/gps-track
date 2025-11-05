<?php

declare(strict_types=1);

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Select;

class OrderStatusFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Order Status');
    }

    /**
     * The array of matched parameters.
     *
     * @return array
     */
    public function parameters(): array
    {
        return ['status'];
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
        $value = $this->request->get('status');
        
        if ($value) {
            return $builder->where('status', $value);
        }
        
        return $builder;
    }

    /**
     * Get the display fields.
     */
    public function display(): array
    {
        return [
            Select::make('status')
                ->options([
                    '' => 'All Statuses',
                    'draft' => 'Draft',
                    'pending' => 'Pending Order',
                    'verified' => 'Verified',
                    'completed' => 'Completed',
                    'delivered' => 'Delivered',
                ])
                ->value($this->request->get('status'))
                ->title(__('Filter by Status')),
        ];
    }

    /**
     * Value to be displayed
     */
    public function value(): string
    {
        $value = $this->request->get('status');
        if ($value) {
            $labels = [
                'draft' => 'Draft',
                'pending' => 'Pending Order',
                'verified' => 'Verified',
                'completed' => 'Completed',
                'delivered' => 'Delivered',
            ];
            return $this->name() . ': ' . ($labels[$value] ?? $value);
        }
        return '';
    }
}

