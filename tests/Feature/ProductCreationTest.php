<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\EcoFeature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductCreationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $admin;
    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->category = Category::factory()->create();
    }

    /** @test */
    public function guest_cannot_access_product_creation()
    {
        $response = $this->get(route('admin.products.create'));
        $response->assertRedirect(route('login'));

        $response = $this->post(route('admin.products.store'), []);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function admin_can_create_product()
    {
        Storage::fake('public');
        
        // Создаем эко-характеристики
        $ecoFeatures = EcoFeature::factory(2)->create();
        
        $productData = [
            'name' => 'Test Product',
            'sku' => 'TEST-1234',
            'description' => 'Test description',
            'price' => 1000,
            'category_id' => $this->category->id,
            'status' => 'published',
            'quantity' => 10,
            'eco_features' => $ecoFeatures->pluck('id')->toArray(),
            'images' => [
                UploadedFile::fake()->image('product1.jpg')
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), $productData);

        $response->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success', 'Продукт успешно создан');

        // Проверяем создание продукта
        $this->assertDatabaseHas('products', [
            'name' => $productData['name'],
            'sku' => $productData['sku'],
            'price' => $productData['price'],
            'quantity' => $productData['quantity']
        ]);

        // Проверяем загрузку изображения и эко-характеристики
        $product = Product::where('sku', $productData['sku'])->first();
        $this->assertCount(1, $product->images);
        $this->assertCount(2, $product->ecoFeatures);
    }

    /** @test */
    public function product_requires_valid_data()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'sku',
            'price',
            'category_id',
            'status',
            'quantity'
        ]);

        // Проверка уникальности SKU
        $existingProduct = Product::factory()->create();
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => 'Test Product',
                'sku' => $existingProduct->sku,
                'price' => 1000,
                'category_id' => $this->category->id,
                'status' => 'published',
                'quantity' => 10
            ]);

        $response->assertSessionHasErrors('sku');
    }
}