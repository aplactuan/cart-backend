<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CartDeleteTest extends TestCase
{
    public function test_it_requires_authentication()
    {
        $this->json('DELETE', '/api/cart/1')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_returns_404_when_product_variation_is_not_available()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('DELETE', '/api/cart/1')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_it_deletes_item_on_cart()
    {
        $user = Passport::actingAs(User::factory()->create());

        $user->cart()->sync(
            $product = ProductVariation::factory()->create()
        );

        $this->json('DELETE', "/api/cart/{$product->id}");

        $this->assertDatabaseMissing('cart_user', [
            'product_variation_id' => $product->id
        ]);
    }
}
