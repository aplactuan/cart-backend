<?php

namespace App\Http\Controllers\Cart;

use App\Cart\Cart;
use App\Http\Controllers\Controller;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class DeleteItemController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param ProductVariation $productVariation
     * @param \Illuminate\Http\Request $request
     *
     */
    public function __invoke(ProductVariation $productVariation, Request $request, Cart $cart)
    {
        $cart->delete($productVariation);
    }
}
