<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SenderAnnouncement;
use App\Models\Message;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Events\MessageSent;
use App\Events\MessageDeleted;
use App\Events\MessageSenderAnnouncement;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Url;
use App\Broadcasting\SenderAnnouncementChannel;

#[Title('Chat')]
class Chat extends Component
{
    public User $selectedUser;

    //public SenderAnnouncement $senderannouncement;

    #[Validate('nullable|string|max:200')]
    public $newMessage;

    public $chatMessages;

    public $senderAnnouncementID;

    public $courierAnnouncementID;

    public $selectedUserAvatar;

    public $authUserAvatar;

    public $timezone;

    #[Url] 
    public $senderannouncement; //for url query string parameter

    #[Url] 
    public $courierannouncement; //for url query string parameter, not used yet

    public $subtitle;

                                     //for both SenderAnnouncement and CourierAnnouncement (would be more readable to make saparate livewire components for both)
    public function mount(User $user, $announcement=null) //, SenderAnnouncement $senderannouncement route model binding doesn't work for SenderAnnouncement. why? | $selectedUser from parent or route model binding  | ,
    {
        $this->selectedUser = $user;

        //$this->senderannouncement = $senderannouncement; //test

        //for Url
        if($this->senderannouncement)
        {
           //dd($this->senderannouncement); 

           $announcement = SenderAnnouncement::findOrFail($this->senderannouncement);
        }

        //for Url
        if($this->courierannouncement)
        {
           //dd($this->courierannouncement); 

           //$announcement = CourierAnnouncement::find($this->courierannouncement); //not created yet
        }

        if($announcement) 
        {
            if(array_key_exists('library', $announcement->getAttributes())) 
            {
                $this->senderAnnouncementID = $announcement->id; //it is a SenderAnnouncement

                new SenderAnnouncementChannel($announcement); //broadcasting authorization endpoint
            }
            else 
            {
                $this->courierAnnouncementID = $announcement->id; //it is a CourierAnnouncement
            } 

            $this->subtitle = 'You can agree on the details of the ad.'; 

            $this->readAllAnnouncementMessages(); //put it outside
        } 
        else
        {
            $this->subtitle = "Talk as much as your heart desires.";
        }

        $this->setMessages(); 

        //get avatars of both users
        if($this->selectedUser->avatar)
        {
            $this->selectedUserAvatar = Storage::url('avatars/'.$this->selectedUser->avatar);
        }

        if(Auth::user()->avatar)
        {
            $this->authUserAvatar = Storage::url('avatars/'.Auth::user()->avatar);
        }

        //set user timezone in db
        $ipInfo = Http::get('http://ip-api.com/json/' . request()->ip());

        $this->timezone = $ipInfo->json()['timezone'] ?? 'Europe/London'; //'Europe/Warsaw'
    }

    public function readAllAnnouncementMessages()
    {
        Message::where('sender_id', $this->selectedUser->id)
            ->where('recipient_id', Auth::user()->id)
            ->where('sender_announcement_id', $this->senderAnnouncementID)
            //->where('courier_announcement_id', $this->courierAnnouncementID) //uncomment later
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
    }

    public function setMessages()
    {
        $this->chatMessages = Message::query()
            ->where(function(Builder $query) {
                $query->where('sender_id', Auth::user()->id)
                    ->where('recipient_id', $this->selectedUser->id)
                    ->where('sender_announcement_id', $this->senderAnnouncementID)
                    /*->where('courier_announcement_id', $this->courierAnnouncementID)*/;
            })
            ->orWhere(function(Builder $query) {
                $query->where('sender_id', $this->selectedUser->id)
                    ->where('recipient_id', Auth::user()->id)
                    ->where('sender_announcement_id', $this->senderAnnouncementID)
                    /*->where('courier_announcement_id', $this->courierAnnouncementID)*/;
            })
            ->get();
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
        ]);

        $this->chatMessages->push($message);

        $this->newMessage = null; 

        if($this->senderAnnouncementID)
        {
            broadcast(new MessageSenderAnnouncement($message))->toOthers();
        }
        else
        {
            broadcast(new MessageSent($message))->toOthers();
        }

        //dd($this->senderAnnouncementID);
    }

    public function updatedNewMessage($property)
    {
        $this->dispatch("userTyping", userID: Auth::user()->id, userName: Auth::user()->name, 
            selectedUserID: $this->selectedUser->id, senderAnnouncementID: $this->senderAnnouncementID);
    }

    public function getListeners()
    {
        $loginID = Auth::user()->id;

        return [
            "echo-private:chat.{$loginID},MessageSent" => 'newChatMessageNotification',
            "echo-private:chat.{$loginID}.{$this->senderAnnouncementID},MessageSenderAnnouncement" => 'messageSenderAnnouncementHandler',
            "echo-private:chat.{$loginID},MessageDeleted" => 'newMessageDeletedNotification'
        ];
    }

    public function newChatMessageNotification($message)
    {
        /*if($message['sender_id'] == $this->selectedUser->id) //auth user is not the sender (to not show auth user's message two times after livewire server roundtrip?)
        {
            //don't show to recipient messages that don't partain to his particular announcement, if he is not on that announcement page
            if($message['sender_announcement_id'] == $this->senderAnnouncementID)
            { */
                $messageModel = Message::find($message['id']);

                $this->chatMessages->push($messageModel);
            //}
        //}
    }

    public function messageSenderAnnouncementHandler($event)
    {
        $messageModel = Message::find($event['id']);

        $this->chatMessages->push($messageModel);
    }

    public function newMessageDeletedNotification()
    {
        $this->setMessages();
    }

    public function deleteMessage($id)
    {
        $message = Message::find($id);
 
        $this->authorize('delete', $message); 

        $message->delete();

        $this->setMessages();

        broadcast(new MessageDeleted($message))->toOthers();

        //dd('message deleted test');
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
