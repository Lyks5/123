<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            AttributeSeeder::class,
            EcoFeaturesSeeder::class,
            ProductSeeder::class,
            ArrivalSeeder::class,
            UserSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
