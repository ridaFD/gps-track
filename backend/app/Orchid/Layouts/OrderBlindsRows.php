<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Group;

class OrderBlindsRows extends Rows
{
    protected $title = 'Blinds';

    protected function fields(): iterable
    {
        $fields = [];
        
        // Get existing blinds
        $existingBlinds = $this->query->get('blinds', []);
        $blindsCount = count($existingBlinds);
        
        // Only render rows we actually need: existing + 1 empty row
        // JavaScript will clone rows to add more
        $maxRows = max($blindsCount + 1, 1);
        
        // Create fields for each row
        for ($i = 0; $i < $maxRows; $i++) {
            $blind = $existingBlinds[$i] ?? [];
            
            $group = Group::make([
                Input::make('blinds.' . $i . '.width')
                    ->type('number')
                    ->title('Width (M)')
                    ->step('0.01')
                    ->value($blind['Width (M)'] ?? ($blind['width'] ?? ''))
                    ->placeholder('Width'),
                    
                Input::make('blinds.' . $i . '.height')
                    ->type('number')
                    ->title('Height (M)')
                    ->step('0.01')
                    ->value($blind['Height (M)'] ?? ($blind['height'] ?? ''))
                    ->placeholder('Height'),
                    
                TextArea::make('blinds.' . $i . '.note')
                    ->title('Note')
                    ->rows(2)
                    ->value($blind['Note'] ?? ($blind['note'] ?? ''))
                    ->placeholder('Add a note...'),
                    
                CheckBox::make('blinds.' . $i . '.stock_alert')
                    ->title('⚠️ Stock Alert')
                    ->value($blind['stock_alert'] ?? false)
                    ->placeholder('Mark if color out of stock')
                    ->sendTrueOrFalse(),
                    
                TextArea::make('blinds.' . $i . '.stock_alert_reason')
                    ->title('Alert Reason')
                    ->rows(2)
                    ->value($blind['stock_alert_reason'] ?? '')
                    ->placeholder('Why is this out of stock?')
                    ->help('Optional: Explain the stock issue'),
                    
                Input::make('blinds.' . $i . '.qty')
                    ->type('number')
                    ->title('Qty')
                    ->value($blind['Qty'] ?? ($blind['qty'] ?? 1)),
                    
                Input::make('blinds.' . $i . '.size_m2')
                    ->type('number')
                    ->title('Size (m²)')
                    ->readonly()
                    ->value($blind['Size (m²)'] ?? ($blind['size_m2'] ?? '')),
                    
                Input::make('blinds.' . $i . '.multiplier')
                    ->type('number')
                    ->title('Multiplier')
                    ->value($blind['Multiplier'] ?? ($blind['multiplier'] ?? 10)),
                    
                Input::make('blinds.' . $i . '.extra')
                    ->type('number')
                    ->title('Extra')
                    ->step('0.01')
                    ->value($blind['Extra'] ?? ($blind['extra'] ?? '')),
                    
                Input::make('blinds.' . $i . '.total')
                    ->title('Total')
                    ->readonly()
                    ->value($blind['Total'] ?? ($blind['total'] ?? '0.00')),
                    
                Upload::make('blind_images.' . $i)
                    ->title('Image')
                    ->acceptedFiles('image/*')
                    ->maxFiles(1)
                    ->storage('public')
                    ->value(isset($blind['image_id']) && $blind['image_id'] ? [(int) $blind['image_id']] : null),
                    
                // Placeholder for remove button (will be added by JavaScript)
                Input::make('blind_remove_' . $i)
                    ->type('hidden'),
            ])->autoWidth();
            
            $fields[] = $group;
        }
        
        return $fields;
    }
}
