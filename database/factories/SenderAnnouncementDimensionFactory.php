<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SenderAnnouncementDimension>
 */
class SenderAnnouncementDimensionFactory extends Factory
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
            'length' => fake()->numberBetween(1, 100), 
            'width' => fake()->numberBetween(1, 100),
            'height' => fake()->numberBetween(1, 100)
        ];
    }
}
