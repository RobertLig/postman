<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourierDimension>
 */
class CourierDimensionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'metric_or_imperial' => 'metric', //another model (row) with 'imperial'
            'length' => fake()->optional()->numberBetween(1, 100), 
            'width' => fake()->optional()->numberBetween(1, 100),
            'height' => fake()->optional()->numberBetween(1, 100)
        ];
    }
}
