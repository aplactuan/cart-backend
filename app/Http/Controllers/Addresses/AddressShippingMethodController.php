<?php

namespace App\Http\Controllers\Addresses;

use App\Http\Controllers\Controller;
use App\Http\Resources\Address\ShippingMethodResource;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressShippingMethodController extends Controller
{
    public function __invoke(Address $address, Request $request)
    {
        $this->authorize('view', $address);

        return ShippingMethodResource::collection($address->country->shippingMethods);
    }
}
