<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Courier>
 */
class CourierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'posting_day' => fake()->numberBetween(1, 31),
            'posting_year' => fake()->numberBetween(2024, 2042),
            'posting_hour' => fake()->numberBetween(0, 23),
            'posting_minute' => fake()->numberBetween(0, 59),
            'reception_day' => fake()->numberBetween(1, 31),
            'reception_year' => fake()->numberBetween(2024, 2042),
            'reception_hour' => fake()->numberBetween(0, 23),
            'reception_minute' => fake()->numberBetween(0, 59),
        ];
    }
}
