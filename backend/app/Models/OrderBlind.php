<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBlind extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'qty',
        'width_m',
        'height_m',
        'note',
        'stock_alert',
        'stock_alert_reason',
        'image_attachment_id',
        'image_path',
        'calc_multiplier',
        'extra_charge',
        'total_price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
