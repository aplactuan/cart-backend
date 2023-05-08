<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Laravel\Passport\Passport;
use Tests\TestCase;
use function MongoDB\BSON\toJSON;

class AddressStoreTest extends TestCase
{
    public function test_it_requires_authentication()
    {
        $this->json('POST', '/api/addresses')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_requires_name()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/addresses', [])
            ->assertJsonValidationErrors('name');
    }

    public function test_it_requires_address_1()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/addresses', [])
            ->assertJsonValidationErrors('address_1');
    }

    public function test_it_requires_city()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/addresses', [])
            ->assertJsonValidationErrors('city');
    }

    public function test_it_requires_postal_code()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/addresses', [])
            ->assertJsonValidationErrors('postal_code');
    }

    public function test_it_requires_country_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/addresses', [])
            ->assertJsonValidationErrors('country_id');
    }

    public function test_it_requires_a_valid_country_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/addresses', [
            'country_id' => 1
        ])
            ->assertJsonValidationErrors('country_id');
    }

    public function test_it_adds_a_user_address()
    {
        $user = Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/addresses',
            $address = Address::factory()->raw()
        );

        $this->assertDatabaseHas('addresses', array_merge(['user_id' => $user->id], $address));
    }

    public function test_it_returns_an_address_when_created()
    {
        $user = Passport::actingAs(User::factory()->create());

        $response = $this->json('POST', '/api/addresses',
            $address = Address::factory()->raw()
        );

        $response->assertJsonFragment(
            array_merge(
                ['id' => json_decode($response->getContent())->data->id],
                Arr::except($address, 'country_id')
            )
        );
    }
}
