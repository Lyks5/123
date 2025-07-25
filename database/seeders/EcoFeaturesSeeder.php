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
                'name' => 'Сокращение CO₂',
                'slug' => 'carbon-saved',
                'description' => 'Количество сокращенных выбросов CO₂ по сравнению с обычным производством',
                'unit' => 'кг CO₂',
                'icon' => 'carbon-footprint',
            ],
            [
                'name' => 'Экономия воды',
                'slug' => 'water-saved',
                'description' => 'Количество сэкономленной воды при производстве по сравнению с обычным производством',
                'unit' => 'л',
                'icon' => 'water-drop',
            ],
            [
                'name' => 'Экономия пластика',
                'slug' => 'plastic-saved',
                'description' => 'Количество сэкономленного пластика в производстве',
                'unit' => 'кг',
                'icon' => 'recycle',
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