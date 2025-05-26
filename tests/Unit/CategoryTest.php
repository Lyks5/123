<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_slug_from_name(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description'
        ]);

        $this->assertEquals('test-category', $category->slug);
    }

    public function test_it_generates_unique_slug(): void
    {
        Category::create([
            'name' => 'Test Category',
            'description' => 'Test Description'
        ]);

        $category2 = Category::create([
            'name' => 'Test Category',
            'description' => 'Another Description'
        ]);

        $this->assertEquals('test-category-1', $category2->slug);
    }

    public function test_it_keeps_custom_slug(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'custom-slug',
            'description' => 'Test Description'
        ]);

        $this->assertEquals('custom-slug', $category->slug);
    }
}