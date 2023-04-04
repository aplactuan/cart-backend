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
        $request->user()->load(['cart.product', 'cart.product.variations.stock', 'cart.stock']);

        return (new CartResource(
            $request->user()
        ))->additional([
            'meta' => $this->meta($cart)
        ]);
    }

    protected function meta($cart)
    {
        return [
            'empty' => $cart->isEmpty()
        ];
    }
}
