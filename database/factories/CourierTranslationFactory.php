<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourierTranslation>
 */
class CourierTranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lang_id' => 1, //1 | another model (row) with 2
            'thing' => fake()->realText($maxNbChars = 50), //words(2, true), //'English thing'
            'description' => fake()->optional()->realText($maxNbChars = 200), //text(), //sentence(), //'English Description'
            'posting_place' => fake()->address(),
            'reception_place' => fake()->address(),
            'posting_month' => fake()->monthName(), 
            'reception_month' => fake()->monthName() 
        ];
    }
}
