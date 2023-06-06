<?php

namespace Tests\Unit\Models\Orders;

use App\Models\Address;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sets_the_order_to_default_when_creating()
    {
        $user = User::factory()->create();
        $address = $user->addresses()->create(Address::factory()->raw());

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'address_id' => $address->id
        ]);

        $this->assertEquals('pending', $order->status);
    }

    public function test_it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $address = $user->addresses()->create(Address::factory()->raw());

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'address_id' => $address->id
        ]);

        $this->assertInstanceOf(User::class, $order->user);
    }

    public function test_it_belongs_to_an_address()
    {
        $user = User::factory()->create();
        $address = $user->addresses()->create(Address::factory()->raw());

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'address_id' => $address->id
        ]);

        $this->assertInstanceOf(Address::class, $order->address);
    }

    public function test_it_belongs_to_a_shipping_method()
    {
        $user = User::factory()->create();
        $address = $user->addresses()->create(Address::factory()->raw());

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'address_id' => $address->id
        ]);

        $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
    }
}
