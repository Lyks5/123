<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Получаем разрешенные MIME-типы из конфигурации
        $allowedMimes = implode(',', array_map(function($mime) {
            return str_replace('image/', '', $mime);
        }, Config::get('images.products.mime_types', [])));

        $maxSize = Config::get('images.products.max_size', 5120);
        $maxWidth = Config::get('images.products.max_width', 1200);

        return [
            'arrival_id' => 'nullable|exists:arrivals,id',
            'name' => 'required_without:arrival_id|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $this->product?->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            'stock_quantity' => 'required|integer|min:0',
            'eco_features' => 'nullable|array',
            'eco_features.*' => 'exists:eco_features,id',
            'eco_feature_values' => 'nullable|array',
            'eco_feature_values.*' => 'nullable|numeric|min:0',
            'attribute_values' => 'nullable|array',
            'attribute_values.*' => 'nullable|exists:attribute_values,id',
            'images' => 'nullable|array',
            'images.*' => [
                'nullable',
                'file',
                'mimes:' . $allowedMimes,
                'max:' . $maxSize,
                'dimensions:min_width=200,min_height=200,max_width=' . $maxWidth
            ],
        ];
    }

    public function messages(): array
    {
        $maxSize = Config::get('images.products.max_size', 5120);
        
        return [
            'arrival_id.exists' => 'Выбранное поступление не существует',
            
            'name.required_without' => 'Название продукта обязательно, если не выбрано поступление',
            'name.max' => 'Название продукта не должно превышать 255 символов',
            
            'sku.required' => 'SKU обязателен',
            'sku.max' => 'SKU не должен превышать 50 символов',
            'sku.unique' => 'Такой SKU уже существует',
            
            'price.required' => 'Цена обязательна',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
            
            'category_id.required' => 'Выберите категорию',
            'category_id.exists' => 'Выбранная категория не существует',
            
            'status.required' => 'Статус обязателен',
            'status.in' => 'Некорректный статус',
            
            'images.array' => 'Некорректный формат изображений',
            'images.*.file' => 'Файл должен быть изображением',
            'images.*.mimes' => 'Поддерживаются форматы: jpg, png, webp',
            'images.*.max' => "Размер изображения не должен превышать {$maxSize}KB",
            'images.*.dimensions' => 'Размер изображения должен быть от 200x200px до 1200px по ширине',
            
            'stock_quantity.required' => 'Количество товара обязательно',
            'stock_quantity.integer' => 'Количество должно быть целым числом',
            'stock_quantity.min' => 'Количество не может быть отрицательным',
            
            'eco_features.array' => 'Некорректный формат экохарактеристик',
            'eco_features.*.exists' => 'Выбранная экохарактеристика не существует',
            'eco_feature_values.*.required_with' => 'Укажите значение для выбранной экохарактеристики',
            'eco_feature_values.*.numeric' => 'Значение экохарактеристики должно быть числом',
            'eco_feature_values.*.min' => 'Значение экохарактеристики не может быть отрицательным',
            
            'attribute_values.array' => 'Некорректный формат атрибутов',
            'attribute_values.*.exists' => 'Выбранное значение атрибута не существует',
        ];
    }

    public function attributes(): array
    {
        return [
            'arrival_id' => 'поступление',
            'name' => 'название',
            'sku' => 'артикул',
            'price' => 'цена',
            'category_id' => 'категория',
            'status' => 'статус',
            'stock_quantity' => 'количество',
            'images.*' => 'изображение',
            'eco_features' => 'эко-характеристики',
            'attribute_values' => 'значения атрибутов',
        ];
    }
}