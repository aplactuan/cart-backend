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
}
