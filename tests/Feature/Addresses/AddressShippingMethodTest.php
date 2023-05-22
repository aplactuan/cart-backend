<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddressShippingMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_requires_authentication()
    {
        $this->json('GET', '/api/addresses/1/shipping-method')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_returns_404_when_address_is_not_found()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('GET', '/api/addresses/1/shipping-method')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_it_requires_user_must_own_the_address()
    {
        Passport::actingAs(User::factory()->create());

        $address = Address::factory()->create([
            'user_id' => User::factory()->create()
        ]);

        $address->country->shippingMethods()->attach(
            ShippingMethod::factory()->create()
        );

        $this->json('GET', '/api/addresses/' . $address->id . '/shipping-method')
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_it_returns_a_shipping_method_for_a_given_address()
    {
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
