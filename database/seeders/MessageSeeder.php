<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conversation;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $conversations = Conversation::all();

        foreach ($conversations as $conversation) {

            $users = $conversation->users;

            $senderToggle = true;
            $time = now()->subDays(rand(1, 20));

            for ($i = 0; $i < rand(5, 10); $i++) {

                $user = $senderToggle
                    ? $users[0]
                    : $users[1];

                Message::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $user->id,
                    'body' => fake()->sentence(),
                    'read_at' => rand(0, 1) ? now() : null,
                    'created_at' => $time->copy()->addMinutes($i * rand(5, 30)),
                    'updated_at' => now(),
                ]);

                $senderToggle = !$senderToggle;
            }
        }
    }
}
