<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SenderAnnouncement;
use App\Models\SenderAnnouncementTranslation; 
use App\Models\SenderAnnouncementWeight;
use App\Models\SenderAnnouncementDimension;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //SenderAnnouncement must be created with proportion 1/2 to SenderAnnouncementTranslation, SenderAnnouncementWeight and SenderAnnouncementDimension
        $users = User::factory()->count(1)->create();

        foreach($users as $user)
        {
            $senderannouncements = SenderAnnouncement::factory()
            ->count(2)
            ->for($user)
            ->create(); 

            foreach($senderannouncements as $senderannouncement)
            {
                SenderAnnouncementTranslation::factory() 
                    ->count(2) //always 2 for each senderannouncement
                    ->state(new Sequence(
              ['lang_id' => 1],
                        ['lang_id' => 2],
                    ))
                    ->for($senderannouncement)
                    ->create();

                SenderAnnouncementWeight::factory()
                    ->count(2) //always 2 for each senderannouncement
                    ->state(new Sequence(
              ['metric_or_imperial' => 'metric'],
                        ['metric_or_imperial' => 'imperial'],
                    ))
                    ->for($senderannouncement)
                    ->create(); 

                SenderAnnouncementDimension::factory()
                    ->count(2) //always 2 for each senderannouncement
                    ->state(new Sequence(
              ['metric_or_imperial' => 'metric'],
                        ['metric_or_imperial' => 'imperial'],
                    ))
                    ->for($senderannouncement)
                    ->create();
            }
        }
        

        /* $user = User::factory()
            ->has(SenderAnnouncement::factory()
                ->count(1) */
                /* ->has(SenderAnnouncementTranslation::factory()
                    ->count(2)
                    ->state(new Sequence(
              ['lang_id' => 1],
                        ['lang_id' => 2],
                    ))
                ) */
            /* )
            ->create(); */


        //SenderAnnouncement::factory()->count(1)->create();

        //User::factory(5)->create();

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */
    }
}
