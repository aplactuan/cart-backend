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
}