<?php

namespace Database\Factories;

use App\Models\EcoFeature;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EcoFeatureFactory extends Factory
{
    protected $model = EcoFeature::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'icon' => $this->faker->randomElement(['leaf', 'recycle', 'water', 'energy', 'eco']),
            'is_active' => $this->faker->boolean(80), // 80% шанс быть активным
        ];
    }

    /**
     * Indicate that the feature is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the feature is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}