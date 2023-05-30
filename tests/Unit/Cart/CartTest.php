<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Cart\Money;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\User;
use Database\Factories\ShippingMethodFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_add_products_to_the_cart()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $productVariation = ProductVariation::factory()->create();

        $cart->add([
            ['id' => $productVariation->id, 'quantity' => 2]
        ]);

        $user->refresh();

        $this->assertCount(1, $user->cart);
        $this->assertEquals($productVariation->id, $user->cart->first()->pivot->product_variation_id);
    }

    public function test_it_can_update_cart_product_items()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $productVariation = ProductVariation::factory()->create();

        $cart->add([
            ['id' => $productVariation->id, 'quantity' => 2]
        ]);

        $cart = new Cart(
            $user->refresh()
        );

        $cart->update($productVariation, 4);

        $this->assertEquals(4, $user->fresh()->cart->first()->pivot->quantity);
    }

    public function test_it_adds_the_quantity_of_previous_products()
    {
        $productVariation = ProductVariation::factory()->create();

        $cart = new Cart(
            $user = User::factory()->create()
        );

        $cart->add([
            ['id' => $productVariation->id, 'quantity' => 2]
        ]);

        $cart = new Cart(
            $user->refresh()
        );

        $cart->add([
            ['id' => $productVariation->id, 'quantity' => 2]
        ]);

        $this->assertEquals(4, $user->fresh()->cart->first()->pivot->quantity);
    }

    public function test_it_can_delete_product_from_cart()
    {
        $productVariation = ProductVariation::factory()->create();

        $cart = new Cart(
            $user = User::factory()->create()
        );

        $cart->add([
            ['id' => $productVariation->id, 'quantity' => 2]
        ]);

        $cart->delete($productVariation);

        $this->assertEquals(0, $user->cart->count());
    }

    public function test_it_can_empty_the_cart()
    {
        $user = User::factory()->create();

        $user->cart()->attach(
            ProductVariation::factory()->create()
        );

        $user->cart()->attach(
            ProductVariation::factory()->create()
        );

        $cart = new Cart($user);

        $cart->empty();

        $this->assertEquals(0, $user->cart->count());
    }

    public function test_it_can_check_if_cart_is_empty()
    {
        $productVariation = ProductVariation::factory()->create();

        $cart = new Cart(
            User::factory()->create()
        );

        $cart->add([
            ['id' => $productVariation->id, 'quantity' => 0]
        ]);

        $this->assertTrue($cart->isEmpty());
    }

    public function test_subtotal_is_instance_of_money()
    {
        $cart = new Cart(
            User::factory()->create()
        );

        $this->assertInstanceOf(Money::class, $cart->subtotal());
    }

    public function test_it_returns_the_correct_subtotal()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            ProductVariation::factory()->create([
                'price' => 500
            ]), [
                'quantity' => 2
            ]
        );

        $this->assertEquals(1000, $cart->subtotal()->amount());
    }

    public function test_total_is_instance_of_money()
    {
        $cart = new Cart(
            User::factory()->create()
        );

        $this->assertInstanceOf(Money::class, $cart->total());
    }

    public function test_it_syncs_the_cart_to_update_quantities()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            ProductVariation::factory()->create(), [
                'quantity' => 2
            ]
        );

        $cart->sync();

        $this->assertEquals(0, $user->cart()->first()->pivot->quantity);
    }

    public function test_it_detects_change_when_cart_is_change_after_synching()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            ProductVariation::factory()->create(), [
                'quantity' => 2
            ]
        );

        $cart->sync();

        $this->assertTrue($cart->hasChanged());
    }

    public function test_it_can_detect_the_minimum_quantity()
    {
        $variation = ProductVariation::factory()->create();

        $variation->stocks()->create([
            'quantity' => $quantity = 5
        ]);

        $this->assertEquals($variation->minStock(200), $quantity);
    }

    public function test_it_can_compute_correct_total_without_shipping()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $user->cart()->attach(
            ProductVariation::factory()->create([
                'price' => 1000
            ]), [
                'quantity' => 2
            ]
        );

        $this->assertEquals(2000, $cart->total()->amount());
    }

    public function test_it_can_compute_correct_total_with_shipping()
    {
        $cart = new Cart(
            $user = User::factory()->create()
        );

        $shipping = ShippingMethod::factory()->create([
            'price' => 200
        ]);

        $user->cart()->attach(
            ProductVariation::factory()->create([
                'price' => 1000
            ]), [
                'quantity' => 2
            ]
        );

        $cart->withShipping($shipping->id);

        $this->assertEquals(2200, $cart->total()->amount());
    }
}
