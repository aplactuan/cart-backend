<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddressIndexTest extends TestCase
{
    public function test_it_requires_authentication()
    {
        $this->json('GET', '/api/addresses')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_displays_the_user_addresses()
    {
        $user = Passport::actingAs(User::factory()->create());

        $user->addresses()->create(
            $address = Address::factory()->raw()
        );

        $this->json('GET', '/api/addresses')
            ->assertJsonFragment([
                'city' => $address['city']
            ]);
    }
}
