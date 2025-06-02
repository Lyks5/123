<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Размер',
                'type' => 'select',
                'values' => [
                    ['value' => 'XS'],
                    ['value' => 'S'],
                    ['value' => 'M'],
                    ['value' => 'L'],
                    ['value' => 'XL'],
                    ['value' => 'XXL']
                ]
            ],
            [
                'name' => 'Цвет',
                'type' => 'color',
                'values' => [
                    ['value' => 'Черный', 'hex_color' => '#000000'],
                    ['value' => 'Белый', 'hex_color' => '#FFFFFF'],
                    ['value' => 'Красный', 'hex_color' => '#FF0000'],
                    ['value' => 'Зеленый', 'hex_color' => '#00FF00'],
                    ['value' => 'Синий', 'hex_color' => '#0000FF'],
                    ['value' => 'Желтый', 'hex_color' => '#FFFF00'],
                    ['value' => 'Розовый', 'hex_color' => '#FF00FF'],
                    ['value' => 'Голубой', 'hex_color' => '#00FFFF'],
                    ['value' => 'Серый', 'hex_color' => '#808080']
                ]
            ],
            [
                'name' => 'Материал',
                'type' => 'text',
                'values' => [
                    ['value' => 'Полиэстер'],
                    ['value' => 'Хлопок'],
                    ['value' => 'Нейлон'],
                    ['value' => 'Спандекс'],
                    ['value' => 'Эластан'],
                    ['value' => 'Бамбук'],
                    ['value' => 'Шерсть']
                ]
            ],
            [
                'name' => 'Пол',
                'type' => 'select',
                'values' => [
                    ['value' => 'Мужской'],
                    ['value' => 'Женский'],
                    ['value' => 'Унисекс']
                ]
            ],
            [
                'name' => 'Сезон',
                'type' => 'select',
                'values' => [
                    ['value' => 'Зима'],
                    ['value' => 'Весна'],
                    ['value' => 'Лето'],
                    ['value' => 'Осень'],
                    ['value' => 'Всесезонный']
                ]
            ]
        ];

        foreach ($attributes as $attributeData) {
            $attribute = Attribute::create([
                'name' => $attributeData['name'],
                'type' => $attributeData['type']
            ]);

            foreach ($attributeData['values'] as $valueData) {
                $attribute->values()->create([
                    'value' => $valueData['value'],
                    'hex_color' => $valueData['hex_color'] ?? null,
                    'display_order' => 0
                ]);
            }
        }
    }
}