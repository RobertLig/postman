<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DropdownLogin extends Component
{
    #[On('profile-updated')]
    public function render()
    {
        $user = Auth::user();

        $avatar = $user->avatar ? Storage::disk('public')->url($user->avatar) : null;
        //dd($avatar);

        return view('livewire.settings.dropdown-login')->with([
            'avatar' => $avatar
        ]);
    }
}
