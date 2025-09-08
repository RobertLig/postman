<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SenderAnnouncementWeight>
 */
class SenderAnnouncementWeightFactory extends Factory
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
            'weight' => fake()->numberBetween(1, 100),
        ];
    }
}
