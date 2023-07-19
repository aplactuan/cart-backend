<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use App\Events\Orders\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    public function __invoke(StoreOrderRequest $request, Cart $cart)
    {
        if ($cart->isEmpty()) {
            return response(null, 400);
        }

        $order = $this->createOrder($request, $cart);

        $order->products()->sync($cart->products()->forSynching());

        event(new OrderCreated($order));
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
