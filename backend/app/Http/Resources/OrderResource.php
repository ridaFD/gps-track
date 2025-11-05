<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'reference' => $this->reference,
            'customer' => [
                'name' => $this->customer_name,
                'first_name' => $this->customer_first_name,
                'last_name' => $this->customer_last_name,
                'phone' => $this->customer_phone,
                'address' => $this->customer_address,
                'city' => $this->customer_city,
            ],
            'pick_up_in_store' => $this->pick_up_in_store,
            'shipping_cost' => $this->shipping_cost,
            'notes' => $this->notes,
            'status' => $this->status,
            'blind' => [
                'width_cm' => $this->blind_width_cm,
                'height_cm' => $this->blind_height_cm,
                'image_path' => $this->blind_image_path,
                'image_url' => $this->blind_image_path ? asset('storage/' . $this->blind_image_path) : null,
            ],
            'calculator' => [
                'multiplier' => $this->calc_multiplier,
                'extra_charge' => $this->extra_charge,
                'total_amount' => $this->total_amount,
            ],
            'lines' => OrderLineResource::collection($this->whenLoaded('lines')),
            'blinds' => OrderBlindResource::collection($this->whenLoaded('blinds')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

