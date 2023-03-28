<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CartUpdateTest extends TestCase
{
    public function test_it_requires_authentication()
    {
        $this->json('PATCH', '/api/cart/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_returns_404_when_product_variation_is_not_available()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('PATCH', '/api/cart/1')
        ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_it_requires_quantity()
    {
        Passport::actingAs(User::factory()->create());

        $productVariation = ProductVariation::factory()->create();

        $this->json('PATCH', "/api/cart/$productVariation->id")
            ->assertJsonValidationErrors('quantity');
    }

    public function test_it_requires_quantity_to_be_numeric()
    {
        Passport::actingAs(User::factory()->create());

        $productVariation = ProductVariation::factory()->create();

        $this->json('PATCH', "/api/cart/$productVariation->id", [
            'quantity' => 'one'
        ])
            ->assertJsonValidationErrors('quantity');
    }

    public function test_it_requires_quantity_to_be_greater_than_or_equal_to_one()
    {
        Passport::actingAs(User::factory()->create());

        $productVariation = ProductVariation::factory()->create();

        $this->json('PATCH', "/api/cart/$productVariation->id", [
            'quantity' => 0
        ])
            ->assertJsonValidationErrors('quantity');
    }

    public function test_it_updates_the_user_cart_quantity()
    {
        $user = Passport::actingAs(User::factory()->create());

        $user->cart()->attach(
            $productVariation = ProductVariation::factory()->create(), [
                'quantity' => 2
            ]
        );

        $this->json('PATCH', "/api/cart/$productVariation->id", [
            'quantity' => $quantity = 5
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $productVariation->id,
            'quantity' => $quantity
        ]);
    }


}
