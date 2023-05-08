<?php

namespace App\Http\Controllers\Addresses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Addresses\StoreAddressRequest;
use App\Http\Resources\AddressResource;

class AddressStoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  StoreAddressRequest  $request
     * @return AddressResource
     */
    public function __invoke(StoreAddressRequest $request)
    {
        $address = $request->user()->addresses()->create(
            $request->only('name', 'address_1', 'city', 'postal_code', 'country_id')
        );

        return new AddressResource($address);
    }
}
