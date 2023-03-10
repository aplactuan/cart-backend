<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariation>
 */
class ProductVariationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory()->create(),
            'name' => fake()->unique()->name,
            'product_variation_type_id' => ProductVariationType::factory()->create()
        ];
    }
}
