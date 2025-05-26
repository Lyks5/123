<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            ['name' => 'Размер', 'type' => 'select'],
            ['name' => 'Цвет', 'type' => 'color'],
            ['name' => 'Материал', 'type' => 'text'],
            ['name' => 'Пол', 'type' => 'select'],
            ['name' => 'Сезон', 'type' => 'select']
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}