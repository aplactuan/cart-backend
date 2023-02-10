<?php

namespace Tests\Unit\Products;

use App\Cart\Money;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Scoping\Scopes\CategoryScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_uses_the_slug_for_route_key_name()
    {
        $product = new Product();

        $this->assertEquals($product->getRouteKeyName(), 'slug');
    }

    public function test_it_has_many_categories()
    {
        $product = Product::factory()->create();

        $product->categories()->create(
            Category::factory()->raw()
        );

        $this->assertInstanceOf(Collection::class, $product->categories);
        $this->assertInstanceOf(Category::class, $product->categories->first());
    }

    public function test_it_has_many_variation()
    {
        $product = Product::factory()->create();

        ProductVariation::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Collection::class, $product->categories);
        $this->assertInstanceOf(ProductVariation::class, $product->variations->first());
    }

    public function test_it_returns_an_instance_of_money_for_price()
    {
        $product = Product::factory()->create();

        $this->assertInstanceOf(Money::class, $product->price);
    }

    public function test_it_has_a_formatted_price()
    {
        $product = Product::factory()->create([
            'price' => 1000
        ]);

        $this->assertEquals('$10.00', $product->formatted_price);
    }
}
