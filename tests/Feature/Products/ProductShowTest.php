<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
    public function test_it_will_return_404_when_slug_does_not_exist()
    {
        $this->json('GET', '/api/products/not-a-product')
            ->assertStatus(404);
    }

    public function test_it_shows_the_produuct()
    {
        $product = Product::factory()->create();


        $this->json('GET', "/api/products/{$product->slug}")
            ->assertJsonFragment([
                'id' => $product->id
            ]);
    }
}
