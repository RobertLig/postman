<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MonthTranslation;

class MonthTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['month_id' => 1,  'month' => 'January',    'language_id' => 1],
            ['month_id' => 2,  'month' => 'February',   'language_id' => 1],
            ['month_id' => 3,  'month' => 'March',      'language_id' => 1],
            ['month_id' => 4,  'month' => 'April',      'language_id' => 1],
            ['month_id' => 5,  'month' => 'May',        'language_id' => 1],
            ['month_id' => 6,  'month' => 'June',       'language_id' => 1],
            ['month_id' => 7,  'month' => 'July',       'language_id' => 1],
            ['month_id' => 8,  'month' => 'August',     'language_id' => 1],
            ['month_id' => 9,  'month' => 'September',  'language_id' => 1],
            ['month_id' => 10, 'month' => 'October',    'language_id' => 1],
            ['month_id' => 11, 'month' => 'November',   'language_id' => 1],
            ['month_id' => 12, 'month' => 'December',   'language_id' => 1],
            ['month_id' => 1,  'month' => 'styczeń',    'language_id' => 2],
            ['month_id' => 2,  'month' => 'luty',       'language_id' => 2],
            ['month_id' => 3,  'month' => 'marzec',     'language_id' => 2],
            ['month_id' => 4,  'month' => 'kwiecień',   'language_id' => 2],
            ['month_id' => 5,  'month' => 'maj',        'language_id' => 2],
            ['month_id' => 6,  'month' => 'czerwiec',   'language_id' => 2],
            ['month_id' => 7,  'month' => 'lipiec',     'language_id' => 2],
            ['month_id' => 8,  'month' => 'sierpień',   'language_id' => 2],
            ['month_id' => 9,  'month' => 'wrzesień',   'language_id' => 2],
            ['month_id' => 10, 'month' => 'październik','language_id' => 2],
            ['month_id' => 11, 'month' => 'listopad',   'language_id' => 2],
            ['month_id' => 12, 'month' => 'grudzień',   'language_id' => 2],
        ];

        MonthTranslation::insert($data);

        /* MonthTranslation::create([
            'month_id' => 12, 
            'month' => 'grudzień',
            'language_id' => 2
        ]); */
    }
}
