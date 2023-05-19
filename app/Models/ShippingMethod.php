<?php

namespace App\Models;

use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShippingMethod extends Model
{
    use HasFactory, HasPrice;

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }
}
