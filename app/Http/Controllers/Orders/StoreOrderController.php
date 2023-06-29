<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    public function __invoke(StoreOrderRequest $request, Cart $cart)
    {
        $order = $this->createOrder($request, $cart);

        $product = $cart->products()->keyBy('id')->map(function ($product) {
            return [
                'quantity' => $product->pivot->quantity
            ];
        })->toArray();

        $order->products()->sync($product);
    }

    public function createOrder($request, Cart $cart)
    {
        return $request->user()->orders()->create(
            array_merge($request->validated(), [
                'subtotal' => $cart->subtotal()->amount()
            ])
        );
    }
}
