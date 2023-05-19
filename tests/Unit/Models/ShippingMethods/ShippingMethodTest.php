<?php

namespace Tests\Unit\Models\ShippingMethods;

use App\Cart\Money;
use App\Models\Country;
use App\Models\ShippingMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ShippingMethodTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_an_instance_of_money_for_the_price()
    {
        $shipping = ShippingMethod::factory()->create();

        $this->assertInstanceOf(Money::class, $shipping->price);
    }

    public function test_it_has_a_formatted_price()
    {
        $shipping = ShippingMethod::factory()->create([
            'price' => 0
        ]);

        $this->assertEquals($shipping->formatted_price, '$0.00');
    }

    public function test_it_has_many_countries()
    {
        $shipping = ShippingMethod::factory()->create();

        $shipping->countries()->attach(
            Country::factory()->create()
        );

        $this->assertInstanceOf(Country::class, $shipping->countries->first());
    }
}
