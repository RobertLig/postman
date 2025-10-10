<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SenderAnnouncement;
use Illuminate\Support\Facades\Auth;

class MessageBox extends Component
{
    public $senderAnnouncements;

    public function mount() 
    {
        $this->senderAnnouncements = Auth::user()->senderAnnouncements;

        //dd($this->senderAnnouncement);
    }

    public function render()
    {
        return view('livewire.message-box');
    }
}
