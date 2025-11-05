<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BlockedUsers extends Component
{
    //public $blockedUsers; 

    public function mount()
    {
        /* $this->blockedUsers = User::query()
            ->whereIn('id', Auth::user()->blocked) //[Auth::user()->id]
            ->get(); */

        //dd($this->blockedUsers);
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
