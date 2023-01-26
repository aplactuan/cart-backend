<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_a_collection_of_products()
    {
        $product = Product::factory()->create();

        $this->json('GET', '/api/products')
            ->assertJsonFragment([
                'id' => $product->id
            ]);
    }

    public function test_it_has_pagination()
    {
        $this->json('GET', '/api/products')
            ->assertJsonStructure([
                'links', 'meta'
            ]);
    }
}
