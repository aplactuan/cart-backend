<?php

namespace Tests\Unit\Models\Users;

use App\Models\Address;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_it_hash_the_password()
    {
        $user = User::factory()->create([
            'password' => 'cat'
        ]);

        $this->assertNotEquals('cat', $user->password);
    }

    public function test_it_has_cart_item()
    {
        $user = User::factory()->create();

        $user->cart()->attach(ProductVariation::factory()->create());

        $this->assertInstanceOf(ProductVariation::class, $user->cart->first());
    }

    public function test_it_has_quantity_for_every_cart_item()
    {
        $user = User::factory()->create();

        $user->cart()->attach(ProductVariation::factory()->create(), [
            'quantity' => $quantity = 4
        ]);

        $this->assertEquals($quantity, $user->cart->first()->pivot->quantity);
    }

    public function test_it_has_many_address()
    {
        $user = User::factory()->create();

        $user->addresses()->create(Address::factory()->raw());

        $this->assertInstanceOf(Address::class, $user->addresses->first());
    }
}
