<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\User;
use App\Models\Courier;
use App\Models\CourierDimension;
use App\Models\CourierTranslation;
use App\Models\CourierWeight;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()
            ->whereBetween('id', [206, 255])
            ->get();

        //dd($users);

        foreach($users as $user)
        {
            $couriers = Courier::factory()
            ->count(2) //2
            ->for($user)
            ->create(); 

            foreach($couriers as $courier)
            {
                CourierTranslation::factory() 
                    ->count(2) //always 2 for each senderannouncement
                    ->state(new Sequence(
                        ['lang_id' => 1],
                        ['lang_id' => 2],
                    ))
                    ->for($courier)
                    ->create();

                CourierWeight::factory()
                    ->count(2) //always 2 for each senderannouncement
                    ->state(new Sequence(
                        ['metric_or_imperial' => 'metric'],
                        ['metric_or_imperial' => 'imperial'],
                    ))
                    ->for($courier)
                    ->create(); 

                CourierDimension::factory()
                    ->count(2) //always 2 for each senderannouncement
                    ->state(new Sequence(
                        ['metric_or_imperial' => 'metric'],
                        ['metric_or_imperial' => 'imperial'],
                    ))
                    ->for($courier)
                    ->create();
            }
        }
    }
}
