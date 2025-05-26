<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Футболки' => 'Мужские и женские футболки разных размеров',
            'Толстовки' => 'Теплые толстовки с капюшоном',
            'Брюки' => 'Спортивные и повседневные брюки',
            'Аксессуары' => 'Различные аксессуары для спорта',
            'Обувь' => 'Спортивная обувь разных размеров'
        ];

        foreach ($categories as $name => $description) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
             
            ]);
        }
    }
}