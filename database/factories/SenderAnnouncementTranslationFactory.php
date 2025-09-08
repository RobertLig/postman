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
            'lang_id' => 1, //1 or 2 | another model (row) with 2
            'thing' => fake()->words(2, true), //'English thing'
            'description' => fake()->text(), //'English Description'
            'posting_place' => '',
            'reception_place' => '',
            'posting_month' => fake()->randomElement($months),
            'reception_month' => fake()->randomElement($months)
        ];
    }
}
