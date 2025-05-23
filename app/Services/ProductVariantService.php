<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Variant;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductVariantService
{
    /**
     * Генерирует все возможные комбинации атрибутов
     */
    public function generateCombinations(array $attributes): Collection
    {
        $arrays = [];
        foreach ($attributes as $attribute) {
            if (!empty($attribute['values'])) {
                $arrays[] = array_map(function($value) use ($attribute) {
                    return [
                        'attribute_id' => $attribute['id'],
                        'value_id' => $value['id']
                    ];
                }, $attribute['values']);
            }
        }

        $result = [[]];
        foreach ($arrays as $array) {
            $temp = [];
            foreach ($result as $item) {
                foreach ($array as $value) {
                    $temp[] = array_merge($item, [$value]);
                }
            }
            $result = $temp;
        }

        return collect($result);
    }

    /**
     * Проверяет уникальность SKU
     */
    public function validateUniqueSku(string $sku, ?int $excludeVariantId = null): bool
    {
        $query = Variant::where('sku', $sku);
        
        if ($excludeVariantId) {
            $query->where('id', '!=', $excludeVariantId);
        }

        return !$query->exists();
    }

    /**
     * Массовое создание/обновление вариантов
     */
    public function bulkUpsertVariants(Product $product, array $variants): void
    {
        DB::transaction(function () use ($product, $variants) {
            foreach ($variants as $variantData) {
                $variant = Variant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'sku' => $variantData['sku']
                    ],
                    [
                        'price' => $variantData['price'],
                        'stock_quantity' => $variantData['stock_quantity'],
                        'weight' => $variantData['weight'] ?? null,
                    ]
                );

                // Обновляем значения атрибутов
                if (isset($variantData['attributes'])) {
                    $attributeValues = [];
                    foreach ($variantData['attributes'] as $attr) {
                        $attributeValues[$attr['value_id']] = [
                            'attribute_id' => $attr['attribute_id']
                        ];
                    }
                    $variant->attributeValues()->sync($attributeValues);
                }
            }
        });
    }

    /**
     * Рассчитывает общее количество товара
     */
    public function calculateTotalStock(Product $product): int
    {
        return Cache::remember(
            "product_{$product->id}_total_stock",
            now()->addHours(1),
            function () use ($product) {
                return $product->variants()->sum('stock_quantity');
            }
        );
    }

    /**
     * Генерирует уникальный SKU для варианта
     */
    public function generateSku(Product $product, array $attributeValues): string
    {
        $base = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $product->name), 0, 3));
        $suffix = substr(md5(serialize($attributeValues)), 0, 5);
        
        $sku = "{$base}-{$suffix}";
        
        $counter = 1;
        $originalSku = $sku;
        
        while (!$this->validateUniqueSku($sku)) {
            $sku = "{$originalSku}-{$counter}";
            $counter++;
        }
        
        return $sku;
    }
}