<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderBlindResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'qty' => $this->qty,
            'dimensions' => [
                'width_m' => $this->width_m,
                'height_m' => $this->height_m,
            ],
            'note' => $this->note,
            'stock_alert' => $this->stock_alert,
            'stock_alert_reason' => $this->stock_alert_reason,
            'image_path' => $this->image_path,
            'image_url' => $this->image_path ? asset('storage/' . $this->image_path) : null,
            'calculator' => [
                'multiplier' => $this->calc_multiplier,
                'extra_charge' => $this->extra_charge,
                'total_price' => $this->total_price,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

