<?php

declare(strict_types=1);

namespace Database\Factories\Support;

class EUCityPool
{
    public static function cities(): array
    {
        return [
            'Warsaw',
            'Kraków',
            'Gdańsk',
            'Wrocław',
            'Poznań',
            'Berlin',
            'Hamburg',
            'Munich',
            'Prague',
            'Vienna',
            'Bratislava',
            'Budapest',
            'Rome',
            'Milan',
            'Paris',
            'Lyon',
            'Amsterdam',
            'Rotterdam',
            'Madrid',
            'Barcelona',
            'Brussels',
            'Zurich',
            'Copenhagen',
            'Stockholm'
        ];
    }

    public static function randomCity(): string
    {
        return fake()->randomElement(self::cities());
    }

    public static function randomRoute(): array
    {
        $from = self::randomCity();
        $to = self::randomCity();

        while ($to === $from) {
            $to = self::randomCity();
        }

        return [$from, $to];
    }
}
