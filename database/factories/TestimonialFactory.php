<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    public function definition(): array
    {
        return [

            'name' => fake()->name(),

            'role' => fake()->randomElement([
                'Courier',
                'Sender',
                'Frequent traveler',
                'Small business owner',
                'Student',
            ]),

            'rating' => fake()->numberBetween(4, 5),

            'content' => fake()->realText(
                fake()->numberBetween(120, 220)
            ),

            'is_featured' => fake()->boolean(80),

            'is_active' => true,

            'published_at' => now(),
        ];
    }
}
