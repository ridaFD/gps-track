<?php

namespace App\Orchid\Screens;

use App\Orchid\Layouts\BlindCalculatorRows;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class CalculatorScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'blinds' => [],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Blind Calculator';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Calculate blind pricing without creating an order';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            BlindCalculatorRows::class,
            Layout::view('orchid.blind-calculator'),
        ];
    }
}

