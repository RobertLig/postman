<?php

namespace Database\Factories;

use App\Models\Courier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\Support\EUCityPool;

class CourierFactory extends Factory
{
    protected $model = Courier::class;

    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 51),
            'posting_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'reception_at' => fake()->dateTimeBetween('now', '+30 days'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Courier $courier) {

            [$from, $to] = EUCityPool::randomRoute();

            $things = [
                'Can carry small parcels in car',
                'Van courier, EU routes',
                'Traveling passenger with luggage space',
                'Flexible delivery during trip',
                'Road trip courier service'
            ];

            $thing = fake()->randomElement($things);

            // translations EN/PL
            $courier->translations()->createMany([
                [
                    'lang_id' => 1,
                    'thing' => $thing,
                    'description' => "Travelling from $from to $to, space available",
                    'posting_place' => $from,
                    'reception_place' => $to,
                ],
                [
                    'lang_id' => 2,
                    'thing' => $thing,
                    'description' => "Podróż z $from do $to, wolne miejsce",
                    'posting_place' => $from,
                    'reception_place' => $to,
                ],
            ]);

            $courier->dimensions()->createMany([
                [
                    'metric_or_imperial' => 'imperial',
                    'length' => fake()->numberBetween(20, 100),
                    'width' => fake()->numberBetween(20, 80),
                    'height' => fake()->numberBetween(10, 60),
                ],
                [
                    'metric_or_imperial' => 'metric',
                    'length' => fake()->numberBetween(50, 250),
                    'width' => fake()->numberBetween(50, 200),
                    'height' => fake()->numberBetween(20, 150),
                ],
            ]);

            $courier->weights()->createMany([
                [
                    'metric_or_imperial' => 'imperial',
                    'weight' => fake()->numberBetween(1, 60),
                ],
                [
                    'metric_or_imperial' => 'metric',
                    'weight' => fake()->numberBetween(1, 30),
                ],
            ]);
        });
    }
}
