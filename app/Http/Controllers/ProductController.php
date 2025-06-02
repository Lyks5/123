<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        // Проверяем активность товара
        if ($product->status !== 'published') {
            abort(404);
        }

        // Загружаем связанные данные
        $product->load([
            'category', 
            'ecoFeatures', 
            'images', 
            'attributeValues.attribute'
        ]);
        
        // Получаем изображения продукта
        $images = $product->images;
        $product->primary_image = $product->primaryImage()->first();

        // Логируем информацию об атрибутах
        \Log::debug('Product attributes:', [
            'product_id' => $product->id,
            'attribute_values' => $product->attributeValues->toArray()
        ]);

        // Группируем атрибуты по группам для более удобного отображения
        $productAttributes = [];
        
        foreach ($product->attributeValues as $attributeValue) {
            $attribute = $attributeValue->attribute;
            $group = $attribute->type ?? 'general';
            
            if (!isset($productAttributes[$group])) {
                $productAttributes[$group] = [];
            }
            
            // Проверяем, существует ли уже атрибут с таким именем
            $existingAttr = null;
            foreach ($productAttributes[$group] as $attr) {
                if ($attr->name === $attribute->name) {
                    $existingAttr = $attr;
                    break;
                }
            }
            
            if ($existingAttr) {
                // Добавляем значение к существующему атрибуту
                $existingAttr->values[] = (object)[
                    'value' => $attributeValue->value,
                    'hex_color' => $attributeValue->hex_color
                ];
            } else {
                // Создаем новый атрибут
                $productAttributes[$group][] = (object)[
                    'name' => $attribute->name,
                    'values' => [(object)[
                        'value' => $attributeValue->value,
                        'hex_color' => $attributeValue->hex_color
                    ]]
                ];
            }
        }

        // Логируем собранные атрибуты
        \Log::debug('Collected product attributes:', $productAttributes);

        // Получаем отзывы
        $reviews = $product->reviews()
            ->with('user')
            ->latest()
            ->paginate(5);

        // Получаем связанные товары
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'published')
            ->take(4)
            ->get();
        
        // Подготавливаем Эко характеристики для отображения
        $ecoFeatures = $product->ecoFeatures->map(function($feature) {
            return (object)[
                'name' => $feature->name,
                'description' => $feature->description,
                'icon' => $feature->icon,
                'value' => $feature->pivot->value ?? null
            ];
        });

        return view('pages.product', compact(
            'product',
            'reviews',
            'relatedProducts',
            'productAttributes',
            'ecoFeatures',
            'images'
        ));
    }
}
