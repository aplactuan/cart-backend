<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductScoperTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_scope_categories()
    {
        $product = Product::factory()->create();
        $anotherProduct = Product::factory()->create();

        $category = Category::factory()->create();

        $product->categories()->save($category);

        $this->json('GET', "/api/products?category={$category->slug}")
            ->assertJsonCount(1, 'data');
    }
}
