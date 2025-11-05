<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;

class BlindCalculatorRows extends Rows
{
    protected $title = 'Blind Calculator';

    protected function fields(): iterable
    {
        $fields = [];
        
        // Get existing calculations (empty array for new calculator)
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
                    
                // Placeholder for remove button (will be added by JavaScript)
                Input::make('blind_remove_' . $i)
                    ->type('hidden'),
            ]);
            
            $fields[] = $group;
        }
        
        return $fields;
    }
}

