<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductAttributeService
{
    /**
     * Получает все значения атрибутов с кэшированием
     */
    public function getCachedAttributeValues(int $attributeId): Collection
    {
        $cacheKey = "attribute_{$attributeId}_values";
        
        return Cache::remember($cacheKey, now()->addDay(), function () use ($attributeId) {
            return AttributeValue::where('attribute_id', $attributeId)
                ->orderBy('value')
                ->get();
        });
    }

    /**
     * Проверяет уникальность комбинации атрибутов
     */
    public function isUniqueCombination(Product $product, array $combination): bool
    {
        $existingVariants = $product->variants()
            ->with('attributeValues')
            ->get();

        foreach ($existingVariants as $variant) {
            $variantAttributes = $variant->attributeValues
                ->map(fn($av) => [
                    'attribute_id' => $av->pivot->attribute_id,
                    'value_id' => $av->id
                ])
                ->toArray();

            if ($this->compareCombinations($combination, $variantAttributes)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Сравнивает две комбинации атрибутов
     */
    private function compareCombinations(array $combination1, array $combination2): bool
    {
        if (count($combination1) !== count($combination2)) {
            return false;
        }

        $sorted1 = collect($combination1)->sortBy(['attribute_id', 'value_id'])->values()->toArray();
        $sorted2 = collect($combination2)->sortBy(['attribute_id', 'value_id'])->values()->toArray();

        return $sorted1 == $sorted2;
    }

    /**
     * Управляет зависимостями атрибутов
     */
    public function getDependentValues(int $attributeId, array $selectedValues): Collection
    {
        $cacheKey = "dependent_values_{$attributeId}_" . md5(serialize($selectedValues));

        return Cache::remember($cacheKey, now()->addHour(), function () use ($attributeId, $selectedValues) {
            return DB::table('variant_attribute_value as vav1')
                ->join('variant_attribute_value as vav2', 'vav1.variant_id', '=', 'vav2.variant_id')
                ->join('attribute_values as av', 'vav2.value_id', '=', 'av.id')
                ->whereIn('vav1.value_id', $selectedValues)
                ->where('vav2.attribute_id', $attributeId)
                ->select('av.id', 'av.value')
                ->distinct()
                ->get();
        });
    }

    /**
     * Создает новое значение атрибута
     */
    public function createAttributeValue(int $attributeId, string $value): AttributeValue
    {
        $attributeValue = AttributeValue::create([
            'attribute_id' => $attributeId,
            'value' => $value
        ]);

        // Очищаем кэш для этого атрибута
        Cache::forget("attribute_{$attributeId}_values");

        return $attributeValue;
    }

    /**
     * Копирует значения атрибутов между вариантами
     */
    public function copyAttributeValues(int $fromVariantId, int $toVariantId): void
    {
        $sourceVariant = DB::table('variant_attribute_value')
            ->where('variant_id', $fromVariantId)
            ->get();

        $attributeValues = $sourceVariant->map(function ($item) use ($toVariantId) {
            return [
                'variant_id' => $toVariantId,
                'attribute_id' => $item->attribute_id,
                'value_id' => $item->value_id
            ];
        })->toArray();

        DB::table('variant_attribute_value')
            ->where('variant_id', $toVariantId)
            ->delete();

        DB::table('variant_attribute_value')
            ->insert($attributeValues);
    }
}