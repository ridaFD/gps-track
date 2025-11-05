<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;

class Blind extends Model
{
    use HasFactory, Attachable, Filterable;

    protected $fillable = [
        'color',
        'color_code',
        'image_attachment_id',
        'image_path',
        'stock_qty',
        'low_stock_threshold',
        'description',
        'has_details',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_details' => 'boolean',
        'stock_qty' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    /**
     * Get the primary image (for backward compatibility)
     */
    public function primaryImage()
    {
        return $this->belongsTo(Attachment::class, 'image_attachment_id');
    }

    /**
     * Get all blind images using the attachments relationship
     */
    public function blindImages()
    {
        return $this->morphToMany(
            \Orchid\Attachment\Models\Attachment::class,
            'attachmentable',
            'attachmentable',
            'attachmentable_id',
            'attachment_id'
        )
        ->where('attachments.group', 'blinds')
        ->orderBy('sort');
    }

    /**
     * Get the stock status
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->stock_qty === 0) {
            return 'out_of_stock';
        } elseif ($this->stock_qty <= $this->low_stock_threshold) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    /**
     * Check if stock is low
     */
    public function isLowStock(): bool
    {
        return $this->stock_status === 'low_stock';
    }

    /**
     * Check if out of stock
     */
    public function isOutOfStock(): bool
    {
        return $this->stock_status === 'out_of_stock';
    }
}
