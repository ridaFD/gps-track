<?php

namespace App\Orchid\Layouts;

use App\Models\OrderLine;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

class OrderLinesTable extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'lines';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('position', '#')->sort(),
            TD::make('label', 'Label'),
            TD::make('width_mm', 'Width (mm)'),
            TD::make('height_mm', 'Height (mm)'),
            TD::make('image_path', 'Image')
                ->render(fn (OrderLine $line) => $line->image_path ? Link::make('View')->href(url('/storage/' . $line->image_path))->target('_blank') : ''),
            TD::make('updated_at', 'Updated'),
        ];
    }
}
