<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Events\MessageSent;

#[Title('Chat')]
class ChatMessage extends Component
{
    public User $selectedUser;

    #[Validate('nullable|string|max:200')]
    public $newMessage;

    public $chatMessages;

    public $senderAnnouncementID;

    public $courierAnnouncementID;

    public $selectedUserAvatar;

    public $authUserAvatar;

    public function mount(User $user) //route model binding
    {
        $this->selectedUser = $user;

        //check if this is the message from an announcement
        $newestMessage = Message::query()
            ->where('sender_id', $this->selectedUser->id)
            ->where('recipient_id', Auth::user()->id)
            ->latest()
            ->first();

        //dd($newestMessage);

        if($newestMessage)
        {
            if(array_key_exists('sender_announcement_id', $newestMessage->getAttributes())) 
            {
                $this->senderAnnouncementID = $newestMessage->sender_announcement_id; //it is a SenderAnnouncement
            }
            elseif(array_key_exists('courier_announcement_id', $newestMessage->getAttributes()))
            {
                $this->courierAnnouncementID = $newestMessage->courier_announcement_id; //it is a CourierAnnouncement
            } 
        } 

        $this->chatMessages = Message::query()
            ->where(function(Builder $query) {
                $query->where('sender_id', Auth::user()->id)
                    ->where('recipient_id', $this->selectedUser->id)
                    //->where('sender_announcement_id', $this->senderAnnouncementID)
                    /*->where('courier_announcement_id', $this->courierAnnouncementID)*/; 
            })
            ->orWhere(function(Builder $query) {
                $query->where('sender_id', $this->selectedUser->id)
                    ->where('recipient_id', Auth::user()->id)
                    //->where('sender_announcement_id', $this->senderAnnouncementID)
                    /*->where('courier_announcement_id', $this->courierAnnouncementID)*/;
            })
            ->get(); //show all messages between two users

        //get avatars of both users
        if($this->selectedUser->avatar)
        {
            $this->selectedUserAvatar = Storage::url('avatars/'.$this->selectedUser->avatar);
        }

        if(Auth::user()->avatar)
        {
            $this->authUserAvatar = Storage::url('avatars/'.Auth::user()->avatar);
        }
    }

    public function save()
    {
        $this->validate();

        if(!$this->newMessage) {return;}

        $message = Message::create([
            'sender_announcement_id' => $this->senderAnnouncementID,
            //'courier_announcement_id' => $this->courierAnnouncementID, uncomment for courier
            'sender_id' => Auth::user()->id,
            'recipient_id' => $this->selectedUser->id,
            'message' => $this->newMessage,
        ]); //may be a response to announcement or casual conversation

        $this->chatMessages->push($message);

        $this->newMessage = null; 

        broadcast(new MessageSent($message));

        //dd($this->senderAnnouncementID);
    }

    public function getListeners()
    {
        $loginID = Auth::user()->id;

        return [
            "echo-private:chat.{$loginID},MessageSent" => 'newChatMessageNotification',
        ];
    }

    public function newChatMessageNotification($message)
    {
        if($message['sender_id'] == $this->selectedUser->id) //auth user is not the sender (to not show auth user's message two times after livewire server roundtrip?)
        {
            $messageModel = Message::find($message['id']);

            $this->chatMessages->push($messageModel);
        }
    }

    public function render()
    {
        return view('livewire.chat-message');
    }
}
