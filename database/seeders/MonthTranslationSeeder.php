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
        MonthTranslation::create([
            'month_id' => 12, 
            'month' => 'grudzień',
            'language_id' => 2
        ]);
    }
}
