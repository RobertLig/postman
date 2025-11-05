<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BlockedUsers extends Component
{
    public function unblockUser(int $id)
    {
        Auth::user()->blocked = Auth::user()->blocked->filter(function (int $value, int $key) use ($id) {
            return $value !== $id;
        });

        Auth::user()->save();
    }

    public function render()
    {
        $blockedUsers = User::query()
            ->whereIn('id', Auth::user()->blocked) //[Auth::user()->id]
            ->get();

        return view('livewire.settings.blocked-users', [
            'blockedUsers' => $blockedUsers,
        ]);
    }
}
