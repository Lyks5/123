<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем 5 обычных пользователей
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Пользователь {$i}",
                'email' => "user{$i}@example.com",
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // пароль password для всех пользователей
                'remember_token' => Str::random(10),
                'is_admin' => false,
            ]);
        }

        // Создаем админа
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'is_admin' => true,
        ]);
    }
}