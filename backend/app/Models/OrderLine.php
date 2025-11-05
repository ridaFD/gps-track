<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'position',
        'width_mm',
        'height_mm',
        'image_path',
        'label',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
