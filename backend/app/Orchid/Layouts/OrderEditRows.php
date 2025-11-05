<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Field;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\RadioButtons;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\CheckBox;

class OrderEditRows extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get cities options from JSON file
     *
     * @return array
     */
    protected function getCitiesOptions(): array
    {
        $options = [];
        
        // Add empty option as default
        $options[''] = 'Please select a city';
        
        $citiesFile = base_path('cities.json');
        if (file_exists($citiesFile)) {
            $cities = json_decode(file_get_contents($citiesFile), true);
            if (is_array($cities)) {
                // Add cities as key => value pairs
                foreach ($cities as $city) {
                    $options[$city] = $city;
                }
            }
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
            Input::make('order.reference')
                ->title('Reference')
                ->disabled(),
            Input::make('order.customer_first_name')
                ->title('Customer First Name')
                ->placeholder('First Name'),
            Input::make('order.customer_last_name')
                ->title('Customer Last Name')
                ->placeholder('Last Name'),
            Input::make('order.customer_phone')
                ->type('tel')
                ->title('Customer Phone')
                ->placeholder('Phone number')
                ->help('Enter customer phone number with country code'),
            TextArea::make('order.customer_address')
                ->title('Customer Address')
                ->rows(2),
            Select::make('order.customer_city')
                ->title('Customer City')
                ->options($this->getCitiesOptions())
                ->searchable()
                ->placeholder('Please select a city')
                ->empty('Please select a city'),
            CheckBox::make('order.pick_up_in_store')
                ->title('Pick up in store')
                ->sendTrueOrFalse(),
            Input::make('order.shipping_cost')
                ->type('number')
                ->title('Shipping Cost')
                ->step('0.01')
                ->placeholder('0.00'),
            Select::make('order.status')
                ->title('Status')
                ->options([
                    'draft' => 'Draft',
                    'pending' => 'Pending Order',
                    'verified' => 'Verified',
                    'ready_to_ship' => 'Ready to Ship',
                    'completed' => 'Completed',
                    'delivered' => 'Delivered',
                ]),
            TextArea::make('order.notes')
                ->title('Notes')
                ->rows(3),
        ];
    }
}
