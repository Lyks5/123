<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
{
    /**
     * Определяет, авторизован ли пользователь для выполнения запроса.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Получить правила валидации, применимые к запросу.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:attributes,name' . ($this->attribute ? ',' . $this->attribute->id : ''),
            ],
            'type' => [
                'required',
                'string',
                'in:select,radio,checkbox,color',
            ],
            'display_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'is_required' => [
                'boolean',
            ],
            'display_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }

    /**
     * Получить сообщения об ошибках для определенных правил валидации.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Название атрибута обязательно для заполнения',
            'name.string' => 'Название атрибута должно быть текстом',
            'name.max' => 'Название атрибута не должно превышать 255 символов',
            'name.unique' => 'Атрибут с таким названием уже существует',
            'type.required' => 'Тип атрибута обязателен для заполнения',
            'type.string' => 'Тип атрибута должен быть текстом',
            'type.in' => 'Выбран недопустимый тип атрибута',
            'display_name.string' => 'Отображаемое название должно быть текстом',
            'display_name.max' => 'Отображаемое название не должно превышать 255 символов',
            'is_required.boolean' => 'Поле обязательности должно быть логическим значением',
            'display_order.integer' => 'Порядок отображения должен быть целым числом',
            'display_order.min' => 'Порядок отображения не может быть отрицательным',
        ];
    }
}