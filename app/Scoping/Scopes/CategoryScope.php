<?php

namespace App\Scoping\Scopes;

use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;

class CategoryScope implements Scope
{
    public function apply(Builder $query, $value)
    {
        return $query->whereHas('categories', function (Builder $query) use ($value) {
            $query->where('slug', $value);
        });
    }
}
