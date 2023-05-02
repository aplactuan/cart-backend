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
}
