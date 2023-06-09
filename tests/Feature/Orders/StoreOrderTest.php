<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StoreOrderTest extends TestCase
{
    public function test_it_requires_an_authenticated_user()
    {
        $this->json('POST', '/api/orders')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_requires_an_address_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/orders')
            ->assertJsonValidationErrors('address_id');
    }

    public function test_it_requires_an_existing_address_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/orders', [
            'address_id' => 1
        ])
            ->assertJsonValidationErrors('address_id');
    }

    public function test_address_id_must_belongs_to_the_authenticated_user()
    {
        Passport::actingAs(User::factory()->create());

        $address = Address::factory()->create([
            'user_id' => User::factory()->create()
        ]);

        $this->json('POST', '/api/orders', [
            'address_id' => $address->id
        ])
            ->assertJsonValidationErrors('address_id');
    }

    public function test_it_requires_a_shipping_method_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/orders')
            ->assertJsonValidationErrors('shipping_method_id');
    }

    public function test_it_requires_a_created_shipping_method_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/orders', [
            'shipping_method_id' => 1
        ])
            ->assertJsonValidationErrors('shipping_method_id');
    }
}
