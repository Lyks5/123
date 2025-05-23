<?php

namespace Database\Factories;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'type' => $this->faker->randomElement(['select', 'radio', 'checkbox', 'color']),
            'display_name' => $this->faker->sentence(2),
            'is_required' => $this->faker->boolean(),
            'display_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}