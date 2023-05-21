<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddressShippingMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_a_shipping_method_for_a_given_address()
    {
        $this->withoutExceptionHandling();
        $user = Passport::actingAs(User::factory()->create());

        //create an address
        $address = $user->addresses()->create(
            Address::factory()->raw([
                    'country_id' => $country = Country::factory()->create()
                ])
        );

        //create a shipping method
        $country->shippingMethods()->attach(
            $shippingMethod = ShippingMethod::factory()->create()
        );

        //get url and check if the shipping method is available
        $this->json('GET', '/api/addresses/' . $address->id . '/shipping-method')
            ->assertJsonFragment([
                'name' => $shippingMethod->name,
                'price' => $shippingMethod->formattedPrice
            ]);
    }
}
