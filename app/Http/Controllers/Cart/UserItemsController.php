<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
use Illuminate\Http\Request;

class UserItemsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return CartResource
     */
    public function __invoke(Request $request, Cart $cart): CartResource
    {
        $cart->sync();

        $request->user()->load([
            'cart.product', 'cart.product.variations.stock', 'cart.stock', 'cart.type'
        ]);

        return (new CartResource(
            $request->user()
        ))->additional([
            'meta' => $this->meta($cart),
            'subtotal' => $cart->subtotal()->formatted(),
            'total' => $cart->total(),
            'changed' => $cart->hasChanged()
        ]);
    }

    protected function meta($cart)
    {
        return [
            'empty' => $cart->isEmpty()
        ];
    }
}
