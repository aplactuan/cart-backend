<?php

namespace Tests\Unit\Models\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Tests\TestCase;

class AddressTest extends TestCase
{
    public function test_it_belongs_to_a_user()
    {
        $address = Address::factory()->create([
            'user_id' => $user = User::factory()->create()
        ]);

        $this->assertInstanceOf(User::class, $address->user);
        $this->assertEquals($user->id, $address->user->id);
    }

    public function test_it_has_one_country()
    {
        $address = Address::factory()->create([
            'user_id' => $user = User::factory()->create()
        ]);

        $this->assertInstanceOf(Country::class, $address->country);
    }

    public function test_it_sets_to_not_default_when_creating()
    {
        $user = User::factory()->create();

        $oldAddress = Address::factory()->create([
            'user_id' => $user->id,
            'default' => true
        ]);

        Address::factory()->create([
            'user_id' => $user->id,
            'default' => true
        ]);

        $this->assertEquals(0, $oldAddress->fresh()->default);
    }
}
