<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $attributes = Attribute::all();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 5; $i++) {
                $product = Product::create([
                    'name' => "Тестовый товар {$i} в {$category->name}",
                    'description' => "Описание тестового товара {$i}",
                    'sku' => Str::random(8),
                    'price' => rand(100, 5000),
                    'category_id' => $category->id,
                    'status' => 'published'
                ]);

                // Добавляем случайные атрибуты
                foreach ($attributes->random(2) as $attribute) {
                    $product->attributes()->attach($attribute->id, [
                        'value' => "Значение {$i}"
                    ]);
                }
            }
        }
    }
}