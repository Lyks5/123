<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EcoFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            [
                'name' => 'Углеродный след',
                'slug' => 'carbon-footprint',
                'description' => 'Количество выбросов CO₂, связанных с производством продукта',
                'unit' => 'кг CO₂e',
                'icon' => 'carbon-footprint',
            ],
            [
                'name' => 'Потребление воды',
                'slug' => 'water-consumption',
                'description' => 'Количество воды, использованной при производстве',
                'unit' => 'л',
                'icon' => 'water-drop',
            ],
            [
                'name' => 'Переработанные материалы',
                'slug' => 'recycled-materials',
                'description' => 'Процент переработанных материалов в составе продукта',
                'unit' => '%',
                'icon' => 'recycle',
            ],
            [
                'name' => 'Энергопотребление',
                'slug' => 'energy-consumption',
                'description' => 'Количество энергии, затраченной при производстве',
                'unit' => 'кВт⋅ч',
                'icon' => 'energy',
            ],
            [
                'name' => 'Биоразлагаемость',
                'slug' => 'biodegradability',
                'description' => 'Время полного разложения продукта',
                'unit' => 'месяцы',
                'icon' => 'leaf',
            ],
            [
                'name' => 'Возможность переработки',
                'slug' => 'recyclability',
                'description' => 'Процент материалов, пригодных для переработки',
                'unit' => '%',
                'icon' => 'recycle-bin',
            ],
        ];

        foreach ($features as $feature) {
            DB::table('eco_features')->insert([
                'name' => $feature['name'],
                'slug' => $feature['slug'],
                'description' => $feature['description'],
                'unit' => $feature['unit'],
                'icon' => $feature['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}