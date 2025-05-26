<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Attribute;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;

class ProductAttributeService
{
    public function saveAttributes(Product $product, array $attributes)
    {
        foreach ($attributes as $attributeData) {
            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $attributeData['attribute_id'],
                'value' => json_encode($attributeData['value'])
            ]);
        }
    }

    public function updateAttributes(Product $product, array $attributes)
    {
        // Получаем текущие атрибуты
        $currentAttributes = $product->attributes()->pluck('attribute_id')->toArray();
        $newAttributes = collect($attributes)->pluck('attribute_id')->toArray();

        // Удаляем отсутствующие атрибуты
        $toDelete = array_diff($currentAttributes, $newAttributes);
        if (!empty($toDelete)) {
            $product->attributes()->whereIn('attribute_id', $toDelete)->delete();
        }

        // Обновляем или создаем атрибуты
        foreach ($attributes as $attributeData) {
            ProductAttribute::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'attribute_id' => $attributeData['attribute_id']
                ],
                [
                    'value' => json_encode($attributeData['value'])
                ]
            );
        }
    }

    public function validateAttributeValues(array $attributes)
    {
        $errors = [];

        foreach ($attributes as $attributeData) {
            $attribute = Attribute::find($attributeData['attribute_id']);
            if (!$attribute) {
                $errors[] = "Атрибут не найден: {$attributeData['attribute_id']}";
                continue;
            }

            $value = $attributeData['value'];

            // Проверяем значение в зависимости от типа атрибута
            switch ($attribute->type) {
                case 'TEXT':
                    if (!is_string($value)) {
                        $errors[] = "Значение атрибута {$attribute->name} должно быть строкой";
                    }
                    break;

                case 'NUMBER':
                    if (!is_numeric($value)) {
                        $errors[] = "Значение атрибута {$attribute->name} должно быть числом";
                    }
                    // Проверяем диапазон если есть
                    if ($attribute->validation) {
                        $validation = json_decode($attribute->validation);
                        if (isset($validation->min) && $value < $validation->min) {
                            $errors[] = "Значение атрибута {$attribute->name} должно быть не меньше {$validation->min}";
                        }
                        if (isset($validation->max) && $value > $validation->max) {
                            $errors[] = "Значение атрибута {$attribute->name} должно быть не больше {$validation->max}";
                        }
                    }
                    break;

                case 'SELECT':
                    if ($attribute->options) {
                        $options = json_decode($attribute->options);
                        if (!in_array($value, $options)) {
                            $errors[] = "Недопустимое значение для атрибута {$attribute->name}";
                        }
                    }
                    break;

                case 'MULTI_SELECT':
                    if (!is_array($value)) {
                        $errors[] = "Значение атрибута {$attribute->name} должно быть массивом";
                    } elseif ($attribute->options) {
                        $options = json_decode($attribute->options);
                        foreach ($value as $val) {
                            if (!in_array($val, $options)) {
                                $errors[] = "Недопустимое значение '{$val}' для атрибута {$attribute->name}";
                            }
                        }
                    }
                    break;
            }
        }

        return $errors;
    }

    public function saveDraftAttributes(Product $product, array $attributes)
    {
        // Для черновика используем тот же метод обновления
        $this->updateAttributes($product, $attributes);
    }

    public function getAttributesByType($type = null)
    {
        $query = Attribute::query();
        
        if ($type) {
            $query->where('type', $type);
        }

        return $query->get();
    }

    public function getVariantAttributes()
    {
        return Attribute::where('is_variant', true)->get();
    }
}