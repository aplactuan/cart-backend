<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreOrderRequest $request)
    {
        dd("It should pass validation");
    }
}
