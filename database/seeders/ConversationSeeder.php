<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Sender;
use App\Models\Courier;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 0; $i < 25; $i++) {

            $users = User::inRandomOrder()
                ->take(2)
                ->get();

            // randomly decide sender or courier conversation
            $useSender = rand(0, 1);

            if ($useSender) {

                $announcement = Sender::inRandomOrder()->first();
            } else {

                $announcement = Courier::inRandomOrder()->first();
            }

            $conversation = Conversation::create([
                'conversationable_type' => $announcement::class,
                'conversationable_id' => $announcement->id,
                'created_by' => $users[0]->id,
            ]);

            $conversation->users()->attach([
                $users[0]->id => [
                    'last_read_at' => now()->subMinutes(rand(0, 500))
                ],
                $users[1]->id => [
                    'last_read_at' => rand(0, 1)
                        ? now()
                        : null
                ],
            ]);
        }
    }
}
