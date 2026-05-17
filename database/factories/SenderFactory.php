<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Sender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\Support\EUCityPool;

class SenderFactory extends Factory
{
    protected $model = Sender::class;

    public function definition(): array
    {
        [$from, $to] = EUCityPool::randomRoute();

        return [
            'user_id' => fake()->numberBetween(1, 51),
            'posting_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'reception_at' => fake()->dateTimeBetween('now', '+30 days'),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Sender $sender) {

            [$from, $to] = EUCityPool::randomRoute();

            $items = [
                'Gaming laptop',
                'Antique guitar',
                'Books collection',
                'Fragile glass sculpture',
                'Camera equipment',
                'Sports gear',
                'Vintage watch'
            ];

            $thing = fake()->randomElement($items);

            // translations EN/PL (STRICT ORDER)
            $sender->translations()->createMany([
                [
                    'lang_id' => 1,
                    'thing' => $thing,
                    'description' => "Need transport from $from to $to: $thing",
                    'posting_place' => $from,
                    'reception_place' => $to,
                ],
                [
                    'lang_id' => 2,
                    'thing' => $thing,
                    'description' => "Potrzebny transport z $from do $to: $thing",
                    'posting_place' => $from,
                    'reception_place' => $to,
                ],
            ]);

            // dimensions (imperial/metric strict order)
            $sender->dimensions()->createMany([
                [
                    'metric_or_imperial' => 'imperial',
                    'length' => fake()->numberBetween(10, 80),
                    'width' => fake()->numberBetween(10, 60),
                    'height' => fake()->numberBetween(5, 50),
                ],
                [
                    'metric_or_imperial' => 'metric',
                    'length' => fake()->numberBetween(20, 200),
                    'width' => fake()->numberBetween(20, 150),
                    'height' => fake()->numberBetween(10, 120),
                ],
            ]);

            // weights (imperial/metric strict order)
            $sender->weights()->createMany([
                [
                    'metric_or_imperial' => 'imperial',
                    'weight' => fake()->numberBetween(1, 50),
                ],
                [
                    'metric_or_imperial' => 'metric',
                    'weight' => fake()->numberBetween(1, 25),
                ],
            ]);
        });
    }
}
