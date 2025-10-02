<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;

class Chat extends Component
{
    public User $selectedUser;

    public $announcement;

    #[Validate('nullable|string|max:200')]
    public $message;

    public function mount(User $selectedUser, $announcement=null) //$selectedUser from parent or route model binding
    {
        $this->selectedUser = $selectedUser;

        $this->announcement = $announcement;

        dd($this->announcement);
    }

    public function save()
    {
        $this->validate();

        if(!$this->message) {return;}

        Message::create([
            'sender_announcement_id' => '',
        ]);
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
