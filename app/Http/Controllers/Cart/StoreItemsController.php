<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\StoreItemRequest;
use Illuminate\Http\Request;

class StoreItemsController extends Controller
{
    public function __invoke(StoreItemRequest $request)
    {
        dd('hello');
    }
}
