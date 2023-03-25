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
        $this->user->cart()->syncWithoutDetaching($this->getStorePayload($products));
    }

    protected function getStorePayload($products): array
    {
        return collect($products)->keyBy('id')
            ->map(function ($product) {
                return [
                    'quantity' => $product['quantity'] + $this->getCurrentQuantity($product['id'])
                ];
            })
            ->toArray();
    }

    public function getCurrentQuantity($productId)
    {
        if ($productVariation = $this->user->cart
            ->where('id', $productId)
            ->first()) {
            return $productVariation->pivot->quantity;
        }
        return 0;
    }

    public function update($productVariation, $quantity)
    {
        $this->user->cart()->updateExistingPivot($productVariation->id, [
            'quantity' => $quantity
        ]);
    }
}
