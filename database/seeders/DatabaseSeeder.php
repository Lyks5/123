<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        
        $this->call(EcoFeaturesSeeder::class);

        User::factory()->create([
            'name' => 'root',
            'email' => 'root@gmail.com',
            'is_admin' => true,
            'password' => bcrypt('rootroot'),
        ]);
    }
}
