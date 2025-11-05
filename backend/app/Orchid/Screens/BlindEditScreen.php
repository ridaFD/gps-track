<?php

namespace App\Orchid\Screens;

use App\Models\Blind;
use App\Orchid\Layouts\BlindEditLayout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Illuminate\Http\RedirectResponse;

class BlindEditScreen extends Screen
{
    public ?Blind $blindModel = null;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Blind $blind = null): iterable
    {
        $this->blindModel = $blind ?? new Blind();

        return [
            'blind' => $this->blindModel->toArray(),
            'blind_images' => $this->blindModel->exists 
                ? $this->blindModel->blindImages()->pluck('attachments.id')->toArray() 
                : [],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->blindModel && $this->blindModel->exists ? 'Edit Blind' : 'Add Blind';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return $this->blindModel && $this->blindModel->exists ? 'Update blind color information' : 'Add a new blind color to catalog';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('bs.save')
                ->method('save'),
            Button::make('Remove')
                ->icon('bs.trash')
                ->method('remove')
                ->canSee($this->blindModel && $this->blindModel->exists),
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
            BlindEditLayout::class,
        ];
    }

    public function save(Blind $blind = null): RedirectResponse
    {
        $data = request()->get('blind', []);

        if (!$blind || !$blind->exists) {
            $blind = new Blind();
        }

        // Handle is_active checkbox
        $data['is_active'] = isset($data['is_active']) && $data['is_active'];

        // Handle has_details checkbox
        $data['has_details'] = isset($data['has_details']) && $data['has_details'];

        // Handle stock_qty - default to 0 if not provided
        if (!isset($data['stock_qty']) || $data['stock_qty'] === null || $data['stock_qty'] === '') {
            $data['stock_qty'] = 0;
        }

        // Handle low_stock_threshold - default to 10 if not provided
        if (!isset($data['low_stock_threshold']) || $data['low_stock_threshold'] === null || $data['low_stock_threshold'] === '') {
            $data['low_stock_threshold'] = 10;
        }

        $blind->fill($data)->save();

        // Sync attachments manually
        $blindImagesData = request()->get('blind_images', []);
        if (is_array($blindImagesData)) {
            $attachmentIds = array_filter(array_map('intval', $blindImagesData));
            $blind->blindImages()->sync($attachmentIds);
        }
        
        Toast::info('Blind saved successfully');
        return redirect()->route('platform.blinds');
    }

    public function remove(Blind $blind): RedirectResponse
    {
        $blind->delete();
        Toast::info('Blind deleted');
        return redirect()->route('platform.blinds');
    }
}

