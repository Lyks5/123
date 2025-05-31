<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'stock_quantity' => (int) $this->stock_quantity,
            'compare_at_price' => $this->compare_at_price ? (float) $this->compare_at_price : null,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            
            // Основное изображение (первое из загруженных)
            'image' => $this->whenLoaded('images', function() {
                $firstImage = $this->images->sortBy('order')->first();
                return $firstImage ? $firstImage->url : null;
            }),
            
            'category' => $this->whenLoaded('category'),
            'attributes' => $this->whenLoaded('attributes', function() {
                return $this->attributes->map(function($attribute) {
                    return [
                        'attribute_id' => $attribute->id,
                        'value' => $attribute->pivot->value,
                    ];
                });
            }),
            'images' => $this->whenLoaded('images', function() {
                return $this->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'url' => $image->url,
                        'order' => $image->order,
                    ];
                });
            }),
        ];
    }
}