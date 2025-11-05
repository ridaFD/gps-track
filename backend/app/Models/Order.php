<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;

class Order extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'reference',
        'customer_name',
        'customer_phone',
        'customer_first_name',
        'customer_last_name',
        'customer_address',
        'customer_city',
        'pick_up_in_store',
        'shipping_cost',
        'notes',
        'status',
        'blind_width_cm',
        'blind_height_cm',
        'blind_image_path',
        'blind_image_attachment_id',
        'calc_multiplier',
        'extra_charge',
        'total_amount',
    ];

    protected $casts = [
        'pick_up_in_store' => 'boolean',
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'extra_charge' => 'decimal:2',
    ];

    public function lines()
    {
        return $this->hasMany(OrderLine::class)->orderBy('position');
    }

    public function blinds()
    {
        return $this->hasMany(OrderBlind::class);
    }

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id'         => Where::class,
        'created_at' => WhereDateStartEnd::class,
        'updated_at' => WhereDateStartEnd::class,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'created_at',
        'updated_at',
    ];
}
