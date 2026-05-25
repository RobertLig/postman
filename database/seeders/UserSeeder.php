<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'John Doe',
            'email' => 'bjohn@interia.eu',
            'is_admin' => 0,
            'email_verified_at' => now(),
            'password' => Hash::make('Keywest777'),
            'avatar' => null,
            'age' => null,
            'gender' => null,
            'remember_token' => null,
            'timezone' => null,
        ]);
    }
}
