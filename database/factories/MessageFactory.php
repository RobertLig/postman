<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //'sender_announcement_id' => 434, //or 433
            //'courier_announcement_id',
            //'sender_id',
            'recipient_id' => 254, //Auth::user()->id is not accessible in factories and seeders
            'message' => fake()->realText($maxNbChars = 100),
        ];
    }
}
