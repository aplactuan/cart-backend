<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const PENDING = 'pending';
    const PROCESSING = 'processing';
    const PAYMENT_FAILED = 'failed';
    const COMPLETED = 'completed';

    protected $fillable = [
        'address_id',
        'shipping_method_id',
        'status',
        'subtotal'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($order) {
            $order->status = self::PENDING;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function products()
    {
        return $this->belongsTomany(ProductVariation::class, 'product_variation_order')
            ->withPivot(['quantity']);
    }
}
