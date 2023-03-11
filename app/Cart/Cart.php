<?php

namespace App\Cart;

use App\Models\User;

class Cart
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function add($products)
    {
        $products = collect($products)->keyBy('id')
            ->map(function ($product) {
                return [
                    'quantity'=> $product['quantity']
                ];
            })->toArray();

        $this->user->cart()->syncWithoutDetaching($products);
    }
}
