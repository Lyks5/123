<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;

class ProductService
{
    public function create(array $data): Product
    {
        \Log::info('ProductService: Creating product with data:', [
            'raw_category_id' => [
                'value' => $data['category_id'],
                'type' => gettype($data['category_id'])
            ],
            'stock_quantity' => $data['stock_quantity'] ?? 0,
            'status' => $data['status'] ?? 'draft'
        ]);
        
        // Валидация и приведение типов
        if (!is_int($data['category_id'])) {
            $data['category_id'] = (int)$data['category_id'];
            \Log::info('ProductService: Converted category_id to int:', [
                'converted_value' => $data['category_id']
            ]);
        }
        
        $product = Product::create($data);
        
        if (isset($data['attributes'])) {
            $this->syncAttributes($product, $data['attributes']);
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
                    // Оставляем только характеристики с непустыми значениями
                    return $value['value'] !== null && $value['value'] !== '';
                })
                ->toArray();

            $this->syncEcoFeaturesWithValues($product, $ecoFeatures);
        }
        
        return $product->fresh(['attributes', 'images', 'ecoFeatures']);
    }
    
    public function update(Product $product, array $data): Product
    {
        \Log::info('ProductService: Updating product with data:', [
            'raw_category_id' => [
                'value' => $data['category_id'],
                'type' => gettype($data['category_id'])
            ],
            'stock_quantity' => $data['stock_quantity'] ?? 'not set',
            'all_data' => $data
        ]);
        
        // Валидация и приведение типов
        if (!is_int($data['category_id'])) {
            $data['category_id'] = (int)$data['category_id'];
            \Log::info('ProductService: Converted category_id to int:', [
                'converted_value' => $data['category_id']
            ]);
        }
        
        $product->update($data);
        
        if (isset($data['attributes'])) {
            $this->syncAttributes($product, $data['attributes']);
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
                    // Оставляем только характеристики с непустыми значениями
                    return $value['value'] !== null && $value['value'] !== '';
                })
                ->toArray();

            $this->syncEcoFeaturesWithValues($product, $ecoFeatures);
        }
        
        return $product->fresh(['attributes', 'images', 'ecoFeatures']);
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
    
    protected function syncAttributes(Product $product, array $attributes): void
    {
        \Log::info('ProductService: Starting attributes sync', [
            'product_id' => $product->id,
            'attribute_count' => count($attributes)
        ]);
        
        try {
            // Валидация и нормализация атрибутов
            $normalizedAttributes = collect($attributes)
                ->filter(function ($item) {
                    // Фильтруем атрибуты без ID или значения
                    return isset($item['attribute_id']) && !empty($item['value']);
                })
                ->map(function ($item) {
                    // Приводим attribute_id к целому числу
                    $attributeId = (int)$item['attribute_id'];
                    
                    \Log::debug('ProductService: Normalizing attribute', [
                        'raw_id' => $item['attribute_id'],
                        'normalized_id' => $attributeId,
                        'value' => $item['value']
                    ]);
                    
                    return [
                        'attribute_id' => $attributeId,
                        'value' => trim((string)$item['value'])
                    ];
                });
            
            // Проверяем уникальность атрибутов
            $duplicateIds = $normalizedAttributes
                ->pluck('attribute_id')
                ->duplicates()
                ->toArray();
                
            if (!empty($duplicateIds)) {
                throw new \InvalidArgumentException(
                    'Duplicate attribute IDs found: ' . implode(', ', $duplicateIds)
                );
            }
            
            // Проверяем существование атрибутов
            $existingAttributeIds = \App\Models\Attribute::whereIn('id', $normalizedAttributes->pluck('attribute_id'))
                ->pluck('id')
                ->toArray();
                
            $invalidIds = array_diff(
                $normalizedAttributes->pluck('attribute_id')->toArray(),
                $existingAttributeIds
            );
            
            if (!empty($invalidIds)) {
                throw new \InvalidArgumentException(
                    'Invalid attribute IDs: ' . implode(', ', $invalidIds)
                );
            }
            
            // Синхронизируем атрибуты
            $syncData = $normalizedAttributes->mapWithKeys(fn($item) => [
                $item['attribute_id'] => ['value' => $item['value']]
            ])->toArray();
            
            \Log::info('ProductService: Syncing normalized attributes', [
                'product_id' => $product->id,
                'sync_data' => $syncData
            ]);
            
            $product->attributes()->sync($syncData);
            
            \Log::info('ProductService: Attributes sync completed', [
                'product_id' => $product->id,
                'synced_count' => count($syncData)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('ProductService: Error syncing attributes', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'attributes' => $attributes,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    
    protected function syncImages(Product $product, array $images): void
    {
        \Log::info('Syncing images for product', [
            'product_id' => $product->id,
            'images_count' => count($images)
        ]);

        // Если передан массив объектов с id, это существующие изображения
        if (isset($images[0]) && isset($images[0]['id'])) {
            $product->images()->sync(
                collect($images)->mapWithKeys(fn($item) => [
                    $item['id'] => ['order' => $item['order'] ?? 0]
                ])
            );
            \Log::info('Synced existing images');
        }
        // Иначе это новые файлы изображений
        else if (!empty($images)) {
            \Log::info('Processing new image files');
            foreach ($images as $index => $image) {
                if ($image instanceof UploadedFile) {
                    try {
                        // Создаем новый запрос с product_id
                        $currentRequest = request();
                        $currentRequest->merge(['product_id' => $product->id]);
                        
                        // Загружаем изображение
                        app(ProductImageService::class)->upload($image);
                        \Log::info('Uploaded new image', ['index' => $index]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to upload image', [
                            'error' => $e->getMessage(),
                            'index' => $index
                        ]);
                    }
                }
            }
            // Обновляем отношения
            $product->load('images');
        }
    }

    protected function syncEcoFeaturesWithValues(Product $product, array $ecoFeatures): void
    {
        try {
            \Log::info('ProductService: Syncing eco features with values', [
                'product_id' => $product->id,
                'eco_features' => $ecoFeatures
            ]);

            $product->ecoFeatures()->sync($ecoFeatures);

        } catch (\Exception $e) {
            \Log::error('ProductService: Error syncing eco features', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'eco_features' => $ecoFeatures
            ]);
            throw $e;
        }
    }
}