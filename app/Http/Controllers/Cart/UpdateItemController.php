<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class UpdateItemController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ProductVariation $productVariation, UpdateCartRequest $request, Cart $cart)
    {
        $cart->update($productVariation, $request->quantity);
    }
}
