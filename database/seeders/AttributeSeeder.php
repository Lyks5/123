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
                'values' => ['XS', 'S', 'M', 'L', 'XL', 'XXL']
            ],
            [
                'name' => 'Цвет',
                'type' => 'color',
                'values' => [
                    'Черный #000000',
                    'Белый #FFFFFF',
                    'Красный #FF0000',
                    'Зеленый #00FF00',
                    'Синий #0000FF',
                    'Желтый #FFFF00',
                    'Розовый #FF00FF',
                    'Голубой #00FFFF',
                    'Серый #808080'
                ]
            ],
            [
                'name' => 'Материал',
                'type' => 'text',
                'values' => ['Полиэстер', 'Хлопок', 'Нейлон', 'Спандекс', 'Эластан', 'Бамбук', 'Шерсть']
            ],
            [
                'name' => 'Пол',
                'type' => 'select',
                'values' => ['Мужской', 'Женский', 'Унисекс']
            ],
            [
                'name' => 'Сезон',
                'type' => 'select',
                'values' => ['Зима', 'Весна', 'Лето', 'Осень', 'Всесезонный']
            ]
        ];

        foreach ($attributes as $attributeData) {
            $attribute = Attribute::create([
                'name' => $attributeData['name'],
                'type' => $attributeData['type']
            ]);

            foreach ($attributeData['values'] as $value) {
                $attribute->values()->create(['value' => $value]);
            }
        }
    }
}