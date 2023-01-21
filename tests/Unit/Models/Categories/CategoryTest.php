<?php

namespace Tests\Unit\Models\Categories;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_children()
    {
        $category = Category::factory()->create();

        $childCategory = $category->children()->create(
            Category::factory()->raw()
        );

        $this->assertInstanceOf(Collection::class, $category->children);
        $this->assertEquals($childCategory->name, $category->children->first()->name);
    }

    public function test_it_can_fetch_parents_only()
    {
        $category = Category::factory()->create();

        $category->children()->create(
            Category::factory()->raw()
        );

        $this->assertCount(1, Category::parents()->get());
    }

    public function test_it_can_be_order()
    {
        $category = Category::factory()->create(['order' => 3]);

        $firstCategory = Category::factory()->create(['order' => 1]);

        $this->assertEquals($firstCategory->name, Category::ordered()->first()->name);
    }
}
