<?php

namespace App\Orchid\Screens;

use App\Models\Blind;
use App\Orchid\Layouts\BlindFiltersLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;

class BlindsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'blinds' => Blind::with('blindImages')
                ->filters(BlindFiltersLayout::class)
                ->orderBy('created_at', 'desc')
                ->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Blinds Catalog';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Browse available blind colors and stock levels';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Manage All')
                ->icon('bs.gear')
                ->route('platform.blinds.manage'),
            Link::make('Add Blind')
                ->icon('bs.plus')
                ->route('platform.blinds.create'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            BlindFiltersLayout::class,
            Layout::view('orchid.blinds-gallery'),
        ];
    }
}

