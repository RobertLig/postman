<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Attributes\On;

class DropdownLogin extends Component
{
    #[On('profile-updated')]
    public function render()
    {
        return view('livewire.settings.dropdown-login');
    }
}
