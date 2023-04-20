<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CartIndexTest extends TestCase
{
    public function test_it_requires_authentication()
    {
        $this->json('GET', '/api/cart')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_gets_the_cart_items()
    {
        $user = Passport::actingAs(User::factory()->create());

        $user->cart()->attach(
            $productVariation = ProductVariation::factory()->create(), [
                'quantity' => 2
            ]
        );

        $this->json('GET', '/api/cart')
            ->assertJsonFragment([
                'id' => $productVariation->id
            ]);
    }

    public function test_it_shows_if_the_product_is_empty()
    {
        $user = Passport::actingAs(User::factory()->create());

        $this->json('GET', '/api/cart')
            ->assertJsonFragment([
                'empty' => true
            ]);
    }

    public function test_it_shows_a_formatted_subtotal()
    {
        $user = Passport::actingAs(User::factory()->create());

        $variation = ProductVariation::factory()->create([
            'price' => 500
        ]);

        $variation->stocks()->create([
            'quantity' => 100
        ]);

        $user->cart()->attach(
            $variation, [
                'quantity' => 2
            ]
        );

        $this->json('GET', '/api/cart')
            ->assertJsonFragment([
                'subtotal' => '$10.00'
            ]);
    }

    public function test_it_shows_a_formatted_total()
    {
        $user = Passport::actingAs(User::factory()->create());

        $variation = ProductVariation::factory()->create([
            'price' => 500
        ]);

        $variation->stocks()->create([
            'quantity' => 100
        ]);

        $user->cart()->attach(
            $variation, [
                'quantity' => 2
            ]
        );

        $this->json('GET', '/api/cart')
            ->assertJsonFragment([
                'total' => '$10.00'
            ]);
    }

    public function test_it_detects_if_cart_quantity_is_change()
    {
        $user = Passport::actingAs(User::factory()->create());

        $user->cart()->attach(
            ProductVariation::factory()->create([
                'price' => 500
            ]), [
                'quantity' => 2
            ]
        );

        $this->json('GET', '/api/cart')
            ->assertJsonFragment([
                'changed' => true
            ]);
    }
}
