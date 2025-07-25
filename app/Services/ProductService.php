<?php

namespace App\Services;

use App\Models\Product;
use App\Models\AttributeValue;
use App\Models\Arrival;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function create(array $data): Product
    {
        \Log::info('ProductService: Creating product with data:', [
            'category_id' => $data['category_id'],
            'stock_quantity' => $data['stock_quantity'] ?? 0,
            'status' => $data['status'] ?? 'draft'
        ]);
        
        // Если SKU не предоставлен, генерируем его
        if (!isset($data['sku'])) {
            $data['sku'] = $this->generateSku($data['name']);
        }
        
        $product = Product::create($data);
        
        if (isset($data['attribute_values'])) {
            $this->syncAttributeValues($product, $data['attribute_values']);
        }
        
        if (isset($data['images'])) {
            $this->syncImages($product, $data['images']);
        }

        if (isset($data['eco_features']) && is_array($data['eco_features'])) {
            $ecoFeatures = collect($data['eco_features'])
                ->mapWithKeys(function ($featureId) use ($data) {
                    return [
                        $featureId => isset($data['eco_feature_values'][$featureId])
                            ? ['value' => $data['eco_feature_values'][$featureId]]
                            : ['value' => null]
                    ];
                })
                ->filter(function ($value) {
                    return $value['value'] !== null && $value['value'] !== '';
                })
                ->toArray();

            $this->syncEcoFeaturesWithValues($product, $ecoFeatures);
        }
        
        return $product->fresh(['attributeValues.attribute', 'images', 'ecoFeatures']);
    }
    
    public function update(Product $product, array $data): Product
    {
        \Log::info('ProductService: Updating product with data:', [
            'product_id' => $product->id,
            'category_id' => $data['category_id'],
            'has_attribute_values' => isset($data['attribute_values'])
        ]);
        
        $product->update($data);
        
        if (isset($data['attribute_values'])) {
            $this->syncAttributeValues($product, $data['attribute_values']);
        }
        
        if (isset($data['images'])) {
            $this->syncImages($product, $data['images']);
        }

        if (isset($data['eco_features']) && is_array($data['eco_features'])) {
            $ecoFeatures = collect($data['eco_features'])
                ->mapWithKeys(function ($featureId) use ($data) {
                    return [
                        $featureId => isset($data['eco_feature_values'][$featureId])
                            ? ['value' => $data['eco_feature_values'][$featureId]]
                            : ['value' => null]
                    ];
                })
                ->filter(function ($value) {
                    return $value['value'] !== null && $value['value'] !== '';
                })
                ->toArray();

            $this->syncEcoFeaturesWithValues($product, $ecoFeatures);
        }
        
        return $product->fresh(['attributeValues.attribute', 'images', 'ecoFeatures']);
    }
    
    protected function syncAttributeValues(Product $product, array $attributeValues): void
    {
        \Log::info('ProductService: Starting attribute values sync', [
            'product_id' => $product->id,
            'values' => $attributeValues
        ]);
        
        try {
            // Валидация входных данных
            // Нормализуем массив значений атрибутов
            $normalizedValues = collect($attributeValues)
                ->filter()
                ->map(function ($attributeValueId) {
                    return ['attribute_value_id' => (int)$attributeValueId];
                });
            
            // Проверяем существование значений атрибутов
            // Проверяем существование значений атрибутов
            $existingValueIds = AttributeValue::whereIn('id', array_values($attributeValues))
                ->pluck('id')
                ->toArray();
            
            $invalidIds = array_diff(
                array_values($attributeValues),
                $existingValueIds
            );
            
            if (!empty($invalidIds)) {
                throw new \InvalidArgumentException(
                    'Invalid attribute value IDs: ' . implode(', ', $invalidIds)
                );
            }
            
            // Синхронизируем значения атрибутов
            $syncData = $normalizedValues->mapWithKeys(function ($item) {
                return [$item['attribute_value_id'] => []];
            })->toArray();
            
            \Log::info('ProductService: Syncing normalized attribute values', [
                'product_id' => $product->id,
                'sync_data' => $syncData
            ]);
            
            $product->attributeValues()->sync($syncData);
            
        } catch (\Exception $e) {
            \Log::error('ProductService: Error syncing attribute values', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'values' => $attributeValues
            ]);
            throw $e;
        }
    }
    
    protected function syncImages(Product $product, array $images): void
    {
        if (empty($images)) {
            return;
        }

        \Log::info('Uploading images for product', [
            'product_id' => $product->id,
            'images_count' => count($images)
        ]);

        $imageService = app(ProductImageService::class);
        
        foreach ($images as $image) {
            if (!$image instanceof UploadedFile) {
                continue;
            }

            try {
                request()->merge(['product_id' => $product->id]);
                $imageService->upload($image);
            } catch (\Exception $e) {
                \Log::error('Failed to upload image', [
                    'error' => $e->getMessage(),
                    'product_id' => $product->id
                ]);
            }
        }

        $product->load('images');
    }

    protected function syncEcoFeaturesWithValues(Product $product, array $ecoFeatures): void
    {
        try {
            \Log::info('ProductService: Syncing eco features with values', [
                'product_id' => $product->id,
                'eco_features' => $ecoFeatures
            ]);

            // Подготавливаем данные для синхронизации
            $syncData = [];
            foreach ($ecoFeatures as $featureId => $data) {
                if (isset($data['value']) && $data['value'] !== '' && $data['value'] !== null) {
                    $syncData[$featureId] = ['value' => $data['value']];
                }
            }

            \Log::info('ProductService: Prepared sync data', [
                'product_id' => $product->id,
                'sync_data' => $syncData
            ]);

            // Синхронизируем эко-характеристики с их значениями
            $product->ecoFeatures()->sync($syncData);

            \Log::info('ProductService: Successfully synced eco features', [
                'product_id' => $product->id,
                'feature_count' => count($syncData)
            ]);

        } catch (\Exception $e) {
            \Log::error('ProductService: Error syncing eco features', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'eco_features' => $ecoFeatures
            ]);
            throw $e;
        }
    }

    public function createDraft(array $data): Product
    {
        $data['status'] = 'draft';
        return $this->create($data);
    }
    
    public function updateDraft(Product $product, array $data): Product
    {
        $data['status'] = 'draft';
        return $this->update($product, $data);
    }
    
    public function publish(Product $product): Product
    {
        $product->update(['status' => 'published']);
        return $product->fresh();
    }

    /**
     * Генерирует SKU на основе названия продукта
     */
    protected function generateSku(string $name): string
    {
        // Транслитерация названия
        $transliterated = str_replace(' ', '-', $name);
        $transliterated = preg_replace('/[^A-Za-z0-9\-]/', '', $transliterated);
        $transliterated = strtoupper($transliterated);
        
        // Добавляем случайное число для уникальности
        $random = mt_rand(1000, 9999);
        
        return substr($transliterated, 0, 5) . '-' . $random;
    }

    public function createFromArrival(array $data): Product
    {
        return DB::transaction(function () use ($data) {
            // Получаем поступление
            $arrival = Arrival::findOrFail($data['arrival_id']);
            
            // Проверяем доступность количества
            if ($arrival->quantity < $data['stock_quantity']) {
                throw new \InvalidArgumentException('Запрошенное количество превышает доступное в поступлении');
            }

            // Проверяем активность поступления
            if (!$arrival->isActive()) {
                throw new \InvalidArgumentException('Поступление не активно');
            }

            // Создаем продукт
            $productData = [
                'name' => $arrival->name,
                'sku' => $this->generateSku($arrival->name),
                'description' => $data['description'],
                'price' => $data['price'],
                'category_id' => $data['category_id'],
                'stock_quantity' => $data['stock_quantity'],
                'status' => $data['status'] ?? 'draft'
            ];

            // Добавляем обязательные эко-характеристики
            if (isset($data['eco_features'])) {
                $productData['eco_features'] = $data['eco_features'];
                $productData['eco_feature_values'] = $data['eco_feature_values'];
            } else {
                throw new \InvalidArgumentException('Эко-характеристики обязательны для создания товара');
            }

            $product = $this->create($productData);

            // Обновляем количество в поступлении
            $arrival->update([
                'quantity' => $arrival->quantity - $data['stock_quantity']
            ]);

            // Если поступление пустое, отмечаем его как использованное
            if ($arrival->quantity == 0) {
                $arrival->update(['status' => 'used']);
            }

            return $product;
        });
    }
}