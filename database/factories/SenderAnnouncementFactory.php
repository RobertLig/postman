<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SenderAnnouncement>
 */
class SenderAnnouncementFactory extends Factory
{
    public Collection $library;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //$user = Auth::user(); //auth user is null. Why?

        //dd('mama');

        $this->library = new Collection();

        $this->library->add(['uuid' => 'https://picsum.photos', 'url' => 'https://picsum.photos']);
        $this->library->add(['uuid' => 'https://picsum.photos', 'url' => 'https://picsum.photos']);

        return [
            //'user_id' => 74, //Auth::user()->id ? | must be real id of the user | doesn't needed if creating user together with sender announcement as a relationship
            'library' => $this->library, //?
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
