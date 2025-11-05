<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlindResource extends JsonResource
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
            'color' => $this->color,
            'color_code' => $this->color_code,
            'description' => $this->description,
            'image_path' => $this->image_path,
            'image_url' => $this->image_path 
                ? (filter_var($this->image_path, FILTER_VALIDATE_URL) 
                    ? $this->image_path 
                    : url('storage/' . $this->image_path))
                : null,
            'primary_image' => $this->when($this->primaryImage, function () {
                $primaryImage = $this->primaryImage;
                $imageUrl = $primaryImage->url ?? null;
                
                // If no url field, try to construct from path if it exists
                if (!$imageUrl && property_exists($primaryImage, 'path') && $primaryImage->path) {
                    if (filter_var($primaryImage->path, FILTER_VALIDATE_URL)) {
                        $imageUrl = $primaryImage->path;
                    } else {
                        $imageUrl = url('storage/' . $primaryImage->path);
                    }
                }
                
                return [
                    'id' => $primaryImage->id ?? null,
                    'url' => $imageUrl,
                ];
            }),
            'images' => $this->when($this->blindImages, function () {
                return $this->blindImages->map(function ($image) {
                    // Use the url accessor which provides the full URL
                    $imageUrl = $image->url ?? null;
                    
                    return [
                        'id' => $image->id,
                        'url' => $imageUrl,
                        'sort' => $image->sort ?? 0,
                    ];
                })->values(); // Reset array keys
            }),
            'stock' => [
                'quantity' => $this->stock_qty,
                'low_stock_threshold' => $this->low_stock_threshold,
                'status' => $this->stock_status,
                'is_low_stock' => $this->isLowStock(),
                'is_out_of_stock' => $this->isOutOfStock(),
            ],
            'has_details' => $this->has_details,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

