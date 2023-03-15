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
        $this->user->cart()->syncWithoutDetaching($this->getProducts($products));
    }

    public function getProducts($products): array
    {
        return collect($products)->keyBy('id')
            ->map(function ($product) {
                return [
                    'quantity' => $product['quantity']
                ];
            })->toArray();
    }
}
