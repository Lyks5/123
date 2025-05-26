<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantAttribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductVariantService
{
    public function saveVariants(Product $product, array $variants)
    {
        foreach ($variants as $variantData) {
            $variant = Variant::create([
                'product_id' => $product->id,
                'sku' => $variantData['sku'],
                'price' => $variantData['price'],
                'sale_price' => $variantData['sale_price'] ?? null,
                'stock_quantity' => $variantData['stock_quantity']
            ]);

            // Сохраняем атрибуты варианта
            if (isset($variantData['attributes'])) {
                $this->saveVariantAttributes($variant, $variantData['attributes']);
            }
        }
    }

    public function updateVariants(Product $product, array $variants)
    {
        // Получаем текущие варианты
        $currentVariants = $product->variants()->pluck('id')->toArray();
        $newVariants = collect($variants)->pluck('id')->filter()->toArray();

        // Удаляем отсутствующие варианты
        $toDelete = array_diff($currentVariants, $newVariants);
        if (!empty($toDelete)) {
            Variant::whereIn('id', $toDelete)->delete();
        }

        // Обновляем или создаем варианты
        foreach ($variants as $variantData) {
            $variant = Variant::updateOrCreate(
                ['id' => $variantData['id'] ?? null],
                [
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'sale_price' => $variantData['sale_price'] ?? null,
                    'stock_quantity' => $variantData['stock_quantity']
                ]
            );

            // Обновляем атрибуты варианта
            if (isset($variantData['attributes'])) {
                $this->updateVariantAttributes($variant, $variantData['attributes']);
            }
        }
    }

    protected function saveVariantAttributes(Variant $variant, array $attributes)
    {
        foreach ($attributes as $attributeData) {
            VariantAttribute::create([
                'variant_id' => $variant->id,
                'attribute_id' => $attributeData['attribute_id'],
                'value' => json_encode($attributeData['value'])
            ]);
        }
    }

    protected function updateVariantAttributes(Variant $variant, array $attributes)
    {
        // Удаляем существующие атрибуты
        $variant->attributes()->delete();

        // Создаем новые атрибуты
        $this->saveVariantAttributes($variant, $attributes);
    }

    public function generateVariants(Product $product, array $attributes)
    {
        // Получаем все возможные комбинации значений атрибутов
        $combinations = $this->generateAttributeCombinations($attributes);
        
        $variants = [];
        foreach ($combinations as $index => $combination) {
            $variants[] = [
                'sku' => $this->generateVariantSku($product->sku, $combination),
                'price' => $product->price,
                'stock_quantity' => 0,
                'attributes' => $combination
            ];
        }

        return $variants;
    }

    protected function generateAttributeCombinations(array $attributes)
    {
        $result = [[]];
        
        foreach ($attributes as $attribute) {
            $values = (array)$attribute['values'];
            $temp = [];
            
            foreach ($result as $combo) {
                foreach ($values as $value) {
                    $temp[] = array_merge($combo, [
                        [
                            'attribute_id' => $attribute['attribute_id'],
                            'value' => $value
                        ]
                    ]);
                }
            }
            
            $result = $temp;
        }

        return $result;
    }

    protected function generateVariantSku($baseSku, array $attributes)
    {
        $parts = array_map(function ($attr) {
            return Str::slug($attr['value']);
        }, $attributes);

        return $baseSku . '-' . implode('-', $parts);
    }

    public function validateVariant(array $variant)
    {
        $errors = [];

        // Проверка обязательных полей
        if (empty($variant['sku'])) {
            $errors[] = 'SKU варианта обязателен';
        }

        if (!isset($variant['price']) || $variant['price'] <= 0) {
            $errors[] = 'Цена варианта должна быть положительным числом';
        }

        if (isset($variant['sale_price']) && $variant['sale_price'] >= $variant['price']) {
            $errors[] = 'Акционная цена должна быть меньше обычной цены';
        }

        if (!isset($variant['stock_quantity']) || $variant['stock_quantity'] < 0) {
            $errors[] = 'Количество товара не может быть отрицательным';
        }

        // Проверка уникальности SKU
        if (!empty($variant['sku'])) {
            $existingVariant = Variant::where('sku', $variant['sku'])
                ->where('id', '!=', $variant['id'] ?? null)
                ->first();

            if ($existingVariant) {
                $errors[] = 'Вариант с таким SKU уже существует';
            }
        }

        return $errors;
    }

    public function saveDraftVariants(Product $product, array $variants)
    {
        // Для черновика используем тот же метод обновления
        $this->updateVariants($product, $variants);
    }
}