<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasChildren
{
    public function scopeParents(Builder $query)
    {
        $query->whereNull('parent_id');
    }
}
