<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sender;
use App\Models\Courier;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Sender::factory()->count(20)->create();
        Courier::factory()->count(15)->create();

        $this->call([
            ConversationSeeder::class,
            MessageSeeder::class,
        ]);
    }
}
