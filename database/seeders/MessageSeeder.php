<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Message;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()
            ->whereIn('id', [239, 238, 237, 236, 235, 234, 233, 232, 231, 230, 229, 228, 227, 226, 225])
            ->get();

        foreach($users as $user)
        {
            Message::factory()
                ->state([
                   'sender_announcement_id' => $user->id > 229 ? 434 : 433,
                ])
                ->for($user)
                ->create();
        }
    }
}
