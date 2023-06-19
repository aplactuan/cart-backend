<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'shipping_method_id' => ShippingMethod::factory()->create()->id,
            'subtotal' => 1000
        ];
    }
}
