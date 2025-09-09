<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SenderAnnouncementTranslation> 
 */
class SenderAnnouncementTranslationFactory extends Factory 
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $months = ['January','February','March','April','May','June','July','August','September','October','November','December'];

        return [
            'lang_id' => 1, //1 | another model (row) with 2
            'thing' => fake()->realText($maxNbChars = 20), //words(2, true), //'English thing'
            'description' => fake()->optional()->realText($maxNbChars = 100), //text(), //sentence(), //'English Description'
            'posting_place' => fake()->address(),
            'reception_place' => fake()->address(),
            'posting_month' => fake()->randomElement($months),
            'reception_month' => fake()->randomElement($months)
        ];
    }
}
