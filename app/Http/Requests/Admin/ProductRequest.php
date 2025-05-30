<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $this->product?->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:draft,published',
            'quantity' => 'required|integer|min:0',
            'eco_features' => 'nullable|array',
            'eco_features.*' => 'exists:eco_features,id',
            'eco_feature_values' => 'nullable|array',
            'eco_feature_values.*' => 'required_with:eco_features.*|numeric|min:0',
            'attributes' => 'nullable|array',
            'attributes.*.attribute_id' => 'nullable|exists:attributes,id',
            'attributes.*.value' => 'required_with:attributes.*.attribute_id|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120|dimensions:min_width=200,min_height=200',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название продукта обязательно',
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
            
            'images.*.image' => 'Файл должен быть изображением',
            'images.*.mimes' => 'Поддерживаются форматы: jpeg, png, jpg, gif',
            'images.*.max' => 'Размер изображения не должен превышать 5MB',
            'images.*.dimensions' => 'Минимальный размер изображения 200x200px',
            
            'quantity.required' => 'Количество товара обязательно',
            'quantity.integer' => 'Количество должно быть целым числом',
            'quantity.min' => 'Количество не может быть отрицательным',
            
            'eco_features.array' => 'Некорректный формат экохарактеристик',
            'eco_features.*.exists' => 'Выбранная экохарактеристика не существует',
            'eco_feature_values.*.required_with' => 'Укажите значение для выбранной экохарактеристики',
            'eco_feature_values.*.numeric' => 'Значение экохарактеристики должно быть числом',
            'eco_feature_values.*.min' => 'Значение экохарактеристики не может быть отрицательным',
            'attributes.array' => 'Некорректный формат атрибутов',
            'attributes.*.attribute_id.exists' => 'Выбранный атрибут не существует',
            'attributes.*.value.required_with' => 'Для выбранного атрибута необходимо указать значение',
        ];
    }
}