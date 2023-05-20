<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressShippingMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_a_shipping_method_for_a_given_address()
    {
        //create an address
        $address = Address::factory()->create([
            'country_id' => $country = Country::factory()->create()
        ]);

        //create a shipping method
        $country->shippingMethods()->attach(
            ShippingMethod::factory()->create()
        );

        //get url and check if the shipping method is available

    }
}
