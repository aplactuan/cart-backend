<?php

namespace Tests\Unit\Models\Products;

use App\Cart\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use App\Models\Stock;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ProductVariationTest extends TestCase
{
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

    public function test_it_has_many_stocks()
    {
        $variation = ProductVariation::factory()->create();

        $variation->stocks()->create(
            Stock::factory()->raw()
        );

        $this->assertInstanceOf(Collection::class, $variation->stocks);
        $this->assertInstanceOf(Stock::class, $variation->stocks->first());
    }

    public function test_it_has_stock()
    {
        $variation = ProductVariation::factory()->create();

        $variation->stocks()->create(
            Stock::factory()->raw()
        );

        $this->assertInstanceOf(Collection::class, $variation->stock);
        $this->assertInstanceOf(ProductVariation::class, $variation->stock->first());
    }

    public function test_stock_has_pivot()
    {
        $variation = ProductVariation::factory()->create();

        $variation->stocks()->create(
            Stock::factory([
                'quantity' => 5
            ])->raw()
        );

        $this->assertEquals(5, $variation->stock()->first()->pivot->stocks);
        $this->assertEquals(1, $variation->stock()->first()->pivot->in_stock);
    }

    public function test_it_can_track_stack_count()
    {
        $variation = ProductVariation::factory()->create();

        $variation->stocks()->create(
            Stock::factory([
                'quantity' => 5
            ])->raw()
        );

        $variation->stocks()->create(
            Stock::factory([
                'quantity' => 5
            ])->raw()
        );

        $this->assertEquals(10, $variation->stockCount());
    }

    public function test_it_can_track_in_stock()
    {
        $variation = ProductVariation::factory()->create();

        $variation->stocks()->create(
            Stock::factory([
                'quantity' => 5
            ])->raw()
        );

        $variation->stocks()->create(
            Stock::factory([
                'quantity' => 5
            ])->raw()
        );

        $this->assertTrue($variation->inStock());
    }
}
