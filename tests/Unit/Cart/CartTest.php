<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Models\ProductVariation;
use App\Models\User;
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
}
