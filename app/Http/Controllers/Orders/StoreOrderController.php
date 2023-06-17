<?php

namespace App\Http\Controllers\Orders;

use App\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    public function __construct(protected Cart $cart)
    {

    }

    public function __invoke(StoreOrderRequest $request)
    {
        $request->user()->orders()->create($request->validated());
    }

    public function createOrder($request)
    {
        $request->user()->orders()->create(
            array_merge($request->validated(), [
                'subtotal' => $this->cart->subtotal()->amount()
            ])
        );
    }
}
