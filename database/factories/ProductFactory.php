<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'sku' => 'SKU-' . Str::random(8),
            'price' => $this->faker->numberBetween(100, 10000),
            'quantity' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'is_featured' => false,
            'eco_score' => $this->faker->numberBetween(0, 100),
            'sustainability_info' => $this->faker->sentence(),
            'carbon_footprint' => $this->faker->randomFloat(2, 0, 100),
        ];
    }

    /**
     * Indicate that the product is published.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
            ];
        });
    }

    /**
     * Indicate that the product is draft.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function draft()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'draft',
            ];
        });
    }

    /**
     * Indicate that the product is archived.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function archived()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'archived',
            ];
        });
    }
}