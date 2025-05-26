<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => [
                'sometimes',
                'image',
                'mimes:jpeg,png,webp',
                'max:' . config('images.products.max_size'),
            ],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'sku' => [
                'required',
                'string',
                'min:4',
                'max:50',
                Rule::unique('products')->ignore($this->route('product')),
            ],
            'price' => ['required', 'numeric', 'min:0.01'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'is_featured' => ['boolean'],
            
            'attributes' => ['sometimes', 'array'],
            'attributes.*.attribute_id' => ['required', 'integer', 'exists:attributes,id'],
            'attributes.*.value' => ['required', 'string', 'max:255'],
            
            'images' => ['sometimes', 'array'],
            'images.*.id' => ['required', 'integer', 'exists:product_images,id'],
            'images.*.order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'Файл должен быть изображением',
            'image.mimes' => 'Допустимые форматы изображений: jpeg, png, webp',
            'image.max' => 'Размер изображения не должен превышать :max КБ',
            'name.required' => 'Название товара обязательно для заполнения',
            'name.min' => 'Название товара должно содержать минимум :min символа',
            'description.required' => 'Описание товара обязательно для заполнения',
            'description.min' => 'Описание товара должно содержать минимум :min символов',
            'sku.required' => 'Артикул обязателен для заполнения',
            'sku.min' => 'Артикул должен содержать минимум :min символа',
            'sku.unique' => 'Товар с таким артикулом уже существует',
            'price.required' => 'Цена обязательна для заполнения',
            'price.min' => 'Цена должна быть больше :min',
            'category_id.required' => 'Выберите категорию товара',
            'category_id.exists' => 'Выбранная категория не существует',
            'status.required' => 'Статус товара обязателен',
            'status.in' => 'Недопустимый статус товара',
            'attributes.*.attribute_id.required' => 'ID характеристики обязателен',
            'attributes.*.attribute_id.exists' => 'Характеристика не существует',
            'attributes.*.value.required' => 'Значение характеристики обязательно',
            'images.*.id.required' => 'ID изображения обязателен',
            'images.*.id.exists' => 'Изображение не существует',
            'images.*.order.required' => 'Порядок изображения обязателен',
        ];
    }
}