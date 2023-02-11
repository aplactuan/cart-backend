<?php

namespace Tests\Unit\Products;

use App\Cart\Money;
use App\Http\Resources\ProductVariationResource;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_one_variation_type()
    {
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(ProductVariationType::class, $variation->type);
    }

    public function test_it_belongs_to_a_product()
    {
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(Product::class, $variation->product);
    }

    public function test_it_returns_an_instance_of_money_for_price()
    {
        $variation = ProductVariation::factory()->create();

        $this->assertInstanceOf(Money::class, $variation->price);
    }

    public function test_it_has_a_formatted_price()
    {
        $variation = ProductVariation::factory()->create([
            'price' => 1000
        ]);

        $this->assertEquals('$10.00', $variation->formatted_price);
    }

    public function test_it_inherit_the_product_price_if_price_is_null()
    {
        $product = Product::factory()->create();

        $variation = ProductVariation::factory()->create([
            'price' => null,
            'product_id' => $product->id
        ]);

        $this->assertEquals($product->price->amount(), $variation->price->amount());
    }

    public function test_it_detect_if_variation_price_varies_from_product_price()
    {
        $product = Product::factory()->create([
            'price' => 1000
        ]);

        $variation = ProductVariation::factory()->create([
            'price' => 1500,
            'product_id' => $product->id
        ]);

        $variationNoPrice = ProductVariation::factory()->create([
            'price' => null,
            'product_id' => $product->id
        ]);

        $this->assertTrue($variation->priceVaries());
        $this->assertFalse($variationNoPrice->priceVaries());
    }
}
