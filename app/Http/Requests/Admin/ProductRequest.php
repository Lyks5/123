<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $productId = $this->route('product');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($productId)
            ],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'quantity' => ['required', 'integer', 'min:0'],
            
            // Валидация изображений
            'images.*' => [
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048', // 2MB максимум
                'dimensions:min_width=600,min_height=600'
            ],
            
            // Валидация вариантов
            'variants' => ['sometimes', 'array'],
            'variants.*.price' => ['required_with:variants', 'numeric', 'min:0'],
            'variants.*.sale_price' => ['nullable', 'numeric', 'min:0', 'lt:variants.*.price'],
            'variants.*.quantity' => ['required_with:variants', 'integer', 'min:0'],
            'variants.*.attribute_values' => ['required_with:variants', 'array'],
            'variants.*.attribute_values.*' => ['required', 'integer', 'exists:attribute_values,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Название продукта обязательно',
            'sku.required' => 'SKU продукта обязателен',
            'sku.unique' => 'Такой SKU уже существует',
            'price.required' => 'Цена продукта обязательна',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
            'images.*.image' => 'Файл должен быть изображением',
            'images.*.mimes' => 'Допустимые форматы: jpeg, png, jpg',
            'images.*.max' => 'Размер изображения не должен превышать 2MB',
            'images.*.dimensions' => 'Минимальный размер изображения 600x600 пикселей',
            'variants.*.price.required_with' => 'Цена варианта обязательна',
            'variants.*.quantity.required_with' => 'Количество варианта обязательно',
            'variants.*.attribute_values.required_with' => 'Атрибуты варианта обязательны',
        ];
    }
}