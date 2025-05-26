<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function create(array $data): Product
    {
        \Log::info('ProductService: Creating product with data:', [
            'raw_category_id' => [
                'value' => $data['category_id'],
                'type' => gettype($data['category_id'])
            ]
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
        
        return $product->fresh(['attributes', 'images']);
    }
    
    public function update(Product $product, array $data): Product
    {
        \Log::info('ProductService: Updating product with data:', [
            'raw_category_id' => [
                'value' => $data['category_id'],
                'type' => gettype($data['category_id'])
            ]
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
        
        return $product->fresh(['attributes', 'images']);
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
            $normalizedAttributes = collect($attributes)->map(function ($item) {
                // Проверяем наличие необходимых полей
                if (!isset($item['attribute_id']) || !isset($item['value'])) {
                    throw new \InvalidArgumentException(
                        'Attribute must have both attribute_id and value fields'
                    );
                }
                
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
        $product->images()->sync(
            collect($images)->mapWithKeys(fn($item) => [
                $item['id'] => ['order' => $item['order']]
            ])
        );
    }
}