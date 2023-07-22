<?php

namespace Tests\Feature\Orders;

use App\Cart\Cart;
use App\Events\Orders\OrderCreated;
use App\Models\Address;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StoreOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_requires_an_authenticated_user()
    {
        $this->json('POST', '/api/orders')
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_it_requires_an_address_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/orders')
            ->assertJsonValidationErrors('address_id');
    }

    public function test_it_requires_an_existing_address_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/orders', [
            'address_id' => 1
        ])
            ->assertJsonValidationErrors('address_id');
    }

    public function test_address_id_must_belongs_to_the_authenticated_user()
    {
        Passport::actingAs(User::factory()->create());

        $address = Address::factory()->create([
            'user_id' => User::factory()->create()
        ]);

        $this->json('POST', '/api/orders', [
            'address_id' => $address->id
        ])
            ->assertJsonValidationErrors('address_id');
    }

    public function test_it_requires_a_shipping_method_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/orders')
            ->assertJsonValidationErrors('shipping_method_id');
    }

    public function test_it_requires_a_created_shipping_method_id()
    {
        Passport::actingAs(User::factory()->create());

        $this->json('POST', '/api/orders', [
            'shipping_method_id' => 1
        ])
            ->assertJsonValidationErrors('shipping_method_id');
    }

    public function test_it_requires_a_shipping_method_valid_for_an_address_id()
    {
        $user = Passport::actingAs(User::factory()->create());

        $address = Address::factory()->create([
            'user_id' => $user->id
        ]);

        $shippingMethod = ShippingMethod::factory()->create();

        $this->json('POST', '/api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shippingMethod->id
        ])
            ->assertJsonValidationErrors('shipping_method_id');
    }

    public function test_it_creates_an_order()
    {
        $user = Passport::actingAs(User::factory()->create());

        list($address, $shipping) = $this->orderDependency($user);

        $this->json('POST', '/api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
        ])->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id
        ]);
    }

    public function test_it_attaches_the_products_to_the_order()
    {
        $user = Passport::actingAs(User::factory()->create());

        $user->cart()->sync(
            $product = $this->productWithStock()
        );

        list($address, $shipping) = $this->orderDependency($user);

        $this->json('POST', '/api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
        ])->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('product_variation_order', [
            'product_variation_id' => $product->id
        ]);
    }

    public function test_it_fires_an_order_created_event()
    {
        Event::fake();

        $user = Passport::actingAs(User::factory()->create());

        $user->cart()->sync(
            $this->productWithStock()
        );

        list($address, $shipping) = $this->orderDependency($user);

        $this->json('POST', '/api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
        ]);

        Event::assertDispatched(OrderCreated::class);
    }

    public function test_it_empties_the_cart()
    {
        $user = Passport::actingAs(User::factory()->create());

        $user->cart()->sync(
            $this->productWithStock()
        );

        $cart = new Cart($user);

        list($address, $shipping) = $this->orderDependency($user);

        $this->json('POST', '/api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
        ]);

        $this->assertTrue($cart->isEmpty());;
    }


    public function test_it_returns_400_when_cart_is_empty()
    {
        $user = Passport::actingAs(User::factory()->create());

        $user->cart()->sync([
                ($product = $this->productWithStock())->id => [
                    'quantity' => 0
                ]
        ]);

        list($address, $shipping) = $this->orderDependency($user);

        $this->json('POST', '/api/orders', [
            'address_id' => $address->id,
            'shipping_method_id' => $shipping->id,
        ])->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    protected function productWithStock()
    {
        $product = ProductVariation::factory()->create();

        Stock::factory()->create([
            'product_variation_id' => $product->id
        ]);

        return $product;
    }

    protected function orderDependency(User $user)
    {
        $address = Address::factory()->create([
            'user_id' => $user->id
        ]);

        $shippingMethod = ShippingMethod::factory()->create();

        $shippingMethod->countries()->attach($address->country_id);

        return [$address, $shippingMethod];
    }
}
