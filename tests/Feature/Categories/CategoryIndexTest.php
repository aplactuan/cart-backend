<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_a_collection_of_categories()
    {
        $categories = Category::factory()
            ->count(3)
            ->create();

        $response = $this->json('GET', '/api/categories');

        $categories->each(function ($category) use ($response){
            $response->assertJsonFragment([
                'slug' => $category->slug
            ]);
        });
    }

    public function test_it_returns_only_parent_categories()
    {
        $category = Category::factory()->create();

        $category->children()->create(
            Category::factory()->raw()
        );

        $this->json('GET', '/api/categories')->assertJsonCount(1, 'data');
    }

    public function test_it_returns_an_ordered_categorie()
    {
        $secondCategory = Category::factory()->create([
            'order' => 2
        ]);

        $firstCategory = Category::factory()->create([
            'order' => 1
        ]);

        $this->json('GET', '/api/categories')->assertSeeInOrder([
            $firstCategory->slug, $secondCategory->slug
        ]);
    }
}
