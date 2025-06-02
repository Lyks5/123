<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArrivalRequest extends FormRequest
{
    /**
     * Определяет, авторизован ли пользователь для выполнения запроса
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации для запроса
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:active,used'],
            'quantity' => ['required', 'integer', 'min:1'],
            'arrival_date' => ['required', 'date'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }
}