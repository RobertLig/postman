<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class Chat extends Component
{
    public User $selectedUser;

    //public $announcement;

    #[Validate('nullable|string|max:200')]
    public $newMessage;

    //public $messages;

    public $senderAnnouncementID;

    public $courierAnnouncementID;

    public function mount(User $selectedUser, $announcement=null) //$selectedUser from parent or route model binding
    {
        $this->selectedUser = $selectedUser;

        //$this->announcement = $announcement; //both SenderAnnouncement and CourierAnnouncement

        if($announcement)
        {
            if(array_key_exists('library', $announcement->getAttributes())) //it is a SenderAnnouncement
            {
                $this->senderAnnouncementID = $announcement->id;
            }
            else //it is a CourierAnnouncement
            {
                $this->courierAnnouncementID = $announcement->id;
            } 
        }

        /* $this->messages = Message::query()
            ->where(function(Builder $query) {
                $query->where('sender_id', Auth::user()->id)
                    ->where('recipient_id', $this->selectedUser->id);
            })
            ->orWhere(function(Builder $query) {
                $query->where('sender_id', $this->selectedUser->id)
                    ->where('recipient_id', Auth::user()->id);
            })
            ->latest()
            ->get(); */

        
    }

    public function save()
    {
        $this->validate();

        if(!$this->newMessage) {return;}

        Message::create([
            'sender_announcement_id' => $this->senderAnnouncementID,
            //'courier_announcement_id' => $this->courierAnnouncementID, uncomment for courier
            'sender_id' => Auth::user()->id,
            'recipient_id' => $this->selectedUser->id,
            'message' => $this->newMessage,
        ]);

        //$this->messages->push($message);

        $this->newMessage = null; 

        //dd($this->senderAnnouncementID);
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
