<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\StoreItemRequest;
use Illuminate\Http\Request;

class StoreItemsController extends Controller
{
    protected Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function __invoke(StoreItemRequest $request)
    {
        dd($request->user());
        $this->cart->add($request->products);
    }
}
