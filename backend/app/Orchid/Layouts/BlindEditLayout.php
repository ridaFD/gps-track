<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Support\Color;
use App\Models\Blind;

class BlindEditLayout extends Rows
{
    /**
     * Get unique blind colors for dropdown
     *
     * @return array
     */
    protected function getColorOptions(): array
    {
        $options = [];
        
        // Add empty option
        $options[''] = 'Select a color...';
        
        // Get unique colors from database
        $colors = Blind::select('color')
            ->distinct()
            ->whereNotNull('color')
            ->where('color', '!=', '')
            ->orderBy('color')
            ->pluck('color')
            ->toArray();
        
        // Add each color as an option
        foreach ($colors as $color) {
            $options[$color] = $color;
        }
        
        return $options;
    }

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Select::make('blind.color')
                ->title('Color Name')
                ->required()
                ->options($this->getColorOptions())
                ->help('Select an existing blind color from the dropdown'),
                
            Input::make('blind.color_code')
                ->title('Color Code (Hex)')
                ->placeholder('#ffffff')
                ->help('Optional: Hex color code for visual representation'),
                
            TextArea::make('blind.description')
                ->title('Description')
                ->rows(3)
                ->placeholder('Optional description or notes about this color')
                ->help('Add any relevant details about this blind color'),
                
            Upload::make('blind_images')
                ->title('Blind Images')
                ->maxFiles(10)
                ->accept('image/*')
                ->help('Upload multiple photos of the blind in this color')
                ->groups('blinds'),
                
            CheckBox::make('blind.has_details')
                ->title('Has Extra Detailing')
                ->sendTrueOrFalse()
                ->help('Check if the blind image has patterns, pictures, or extra detailing (not just a solid color)')
                ->placeholder('Indicates if image contains patterns or detailed designs'),
                
            Input::make('blind.stock_qty')
                ->type('number')
                ->title('Stock Quantity')
                ->placeholder('0')
                ->help('Current stock quantity available'),
                
            Input::make('blind.low_stock_threshold')
                ->type('number')
                ->title('Low Stock Threshold')
                ->placeholder('10')
                ->help('Alert when stock falls below this number'),
                
            CheckBox::make('blind.is_active')
                ->title('Active')
                ->sendTrueOrFalse()
                ->help('Uncheck to hide this blind from the catalog'),
        ];
    }
}

