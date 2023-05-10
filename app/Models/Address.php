<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address_1',
        'city',
        'postal_code',
        'country_id',
        'default',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($address) {
            if ($address->default) {
                $address->user->addresses()->update([
                    'default' => false
                ]);
            }
        });
    }

    public function setDefaultAttribute($value)
    {
        $this->attributes['default'] = ($value === 'true' || $value ? true : false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
