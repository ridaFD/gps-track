<?php

namespace App\Orchid\Screens;

use App\Models\Blind;
use App\Orchid\Layouts\BlindFiltersLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class BlindListScreen extends Screen
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
                ->orderBy('is_active', 'desc')
                ->orderBy('color')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Manage Blinds';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Manage all blind colors and inventory';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
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
            Layout::table('blinds', [
                TD::make('color', 'Color')
                    ->render(function (Blind $blind) {
                        $imageUrl = $blind->image_path;
                        $colorCode = $blind->color_code;
                        
                        if ($blind->blindImages && $blind->blindImages->isNotEmpty()) {
                            $imageUrl = $blind->blindImages->first()->url;
                        }
                        
                        $html = '<div style="display: flex; align-items: center; gap: 8px;">';
                        
                        if ($imageUrl) {
                            $html .= '<img src="' . e($imageUrl) . '" alt="' . e($blind->color) . '" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">';
                        } elseif ($colorCode) {
                            $html .= '<div style="width: 40px; height: 40px; background-color: ' . e($colorCode) . '; border-radius: 4px; border: 1px solid #ddd;"></div>';
                        }
                        
                        $html .= '<span>' . e($blind->color) . '</span>';
                        if ($blind->has_details) {
                            $html .= '<span class="badge bg-info ms-2" title="Has extra detailing, patterns, or pictures">🎨 Details</span>';
                        }
                        $html .= '</div>';
                        
                        return $html;
                    })
                    ->sort()
                    ->filter(TD::FILTER_TEXT),

                TD::make('stock_qty', 'Stock')
                    ->render(function (Blind $blind) {
                        $badgeClass = 'badge bg-success';
                        $badgeText = '✓ ' . $blind->stock_qty;
                        
                        if ($blind->stock_qty === 0) {
                            $badgeClass = 'badge bg-danger';
                            $badgeText = '🚫 0';
                        } elseif ($blind->isLowStock()) {
                            $badgeClass = 'badge bg-warning text-dark';
                            $badgeText = '⚠️ ' . $blind->stock_qty;
                        }
                        
                        return '<span class="' . $badgeClass . '">' . $badgeText . '</span>';
                    })
                    ->sort()
                    ->filter(TD::FILTER_TEXT),

                TD::make('is_active', 'Status')
                    ->render(function (Blind $blind) {
                        if ($blind->is_active) {
                            return '<span class="badge bg-success">Active</span>';
                        }
                        return '<span class="badge bg-secondary">Inactive</span>';
                    })
                    ->sort()
                    ->filter(TD::FILTER_TEXT),

                TD::make('created_at', 'Created')
                    ->render(fn (Blind $blind) => $blind->created_at->format('Y-m-d'))
                    ->sort(),

                TD::make('actions', 'Actions')
                    ->render(function (Blind $blind) {
                        return Link::make('Edit')
                            ->icon('bs.pencil')
                            ->route('platform.blinds.edit', $blind);
                    }),
            ]),
        ];
    }
}

