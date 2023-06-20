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
        //$request->user()->orders()->createOrder($request);
        $this->createOrder($request, $cart);
    }

    public function createOrder($request, $cart)
    {
        $request->user()->orders()->create(
            array_merge($request->validated(), [
                'subtotal' => $cart->subtotal()->amount()
            ])
        );
    }
}
