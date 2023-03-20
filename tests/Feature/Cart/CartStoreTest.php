<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CartStoreTest extends TestCase
{
    public function test_it_requires_to_be_authenticated()
    {
        $this->json('POST', '/api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 1]
            ]
        ])
          ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_requires_a_product()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/cart')
            ->assertJsonValidationErrors('products');
    }

    public function test_products_needs_to_be_an_array()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/cart', [
            'products' => 'products'
        ])
            ->assertJsonValidationErrors('products');
    }

    public function test_it_requires_an_id_foreach_products()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/cart', [
            'products' => [
                ['quantity' => 1]
            ]
        ])
            ->assertJsonValidationErrors('products.0.id');
    }

    public function test_products_needs_to_have_an_existing_product_variation_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 1]
            ]
        ])
            ->assertJsonValidationErrors('products.0.id');
    }

    public function test_it_requires_quantity_for_each_product()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/cart', [
            'products' => [
                ['id' => 1]
            ]
        ])
            ->assertJsonValidationErrors('products.0.quantity');
    }

    public function test_it_requires_quantity_to_be_numeric()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 'three']
            ]
        ])
            ->assertJsonValidationErrors('products.0.quantity');
    }

    public function test_it_requires_quantity_to_be_at_least_1()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/cart', [
            'products' => [
                ['id' => 1, 'quantity' => 0]
            ]
        ])
            ->assertJsonValidationErrors('products.0.quantity');
    }

    public function test_it_stores_product_items_to_user_cart()
    {
        Passport::actingAs(User::factory()->create());

        $product = ProductVariation::factory()->create();

        $this->json('POST', '/api/cart', [
            'products' => [
                ['id' => $product->id, 'quantity' => 2]
            ]
        ]);

        $this->assertDatabaseHas('cart_user', [
            'product_variation_id' => $product->id,
            'quantity' => 2
        ]);
    }
}
