<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SenderAnnouncement;
use Illuminate\Support\Facades\Auth;
//use Livewire\Attributes\On;
use Illuminate\Support\Collection; 
use App\Models\User;

class MessageBox extends Component
{
    public $senderAnnouncements;

    //public $announcementsInbox;

    //public Collection $presentUsers; //doesn't work

    public Collection $users;

    public function mount() 
    {
        $this->senderAnnouncements = Auth::user()->senderAnnouncements;

        //dd($this->senderAnnouncement);

        //$this->presentUsers = new Collection(); //doesn't work

        $this->users = new Collection();

        //messages related to announcements sent to logged in user
        foreach($this->senderAnnouncements as $senderAnnouncement)
        {
            $messages = $senderAnnouncement->messages;

            foreach($messages as $message)
            {
                if($message->sender_id != Auth::user()->id)
                {
                    $user = User::find($message->sender_id);

                    $this->users->push($user);
                }
            }
        }

        //dd($messages);
    }

    public function getListeners()
    {
        $loginID = Auth::user()->id;

        return [
            "echo-private:chat.{$loginID},MessageSent" => 'newChatMessageNotification',
            //"echo-private:chat.{$loginID},MessageDeleted" => 'newMessageDeletedNotification'
        ];
    }

    public function newChatMessageNotification($message)
    {
        if($message['sender_announcement_id']) //if this is a message about annnouncement
        { 
            $user = User::find($message['sender_id']);

            $this->users->push($user);

            //SenderAnnouncement::find($message['sender_announcement_id']);

            //$this->announcementsInbox = 

            //dd('announcements inbox');

            //$messageModel = Message::find($message['id']);

            //$this->chatMessages->push($messageModel);
        }
    }

    /* doesn't work public function getListeners()
    {
        return [
            "echo-presence:chatroom,UserEnterAnnouncement" => 'newUsersNotification', //? //"echo-presence:senderannouncement.{sender_announcement_id},UserEnterAnnouncement"
            "echo-presence:chatroom,here" => 'here',
            "echo-presence:chatroom,joining" => 'joining',
            "echo-presence:chatroom,leaving" => 'leaving',
        ];
    } 

    public function newUsersNotification()
    {
        //dd("I am on show announcement page");
    }

    //#[On('echo-presence:chatroom,here')]
    public function here($users)
    {
        foreach($users as $user)
        {
            $user = User::find($user['id']);

            $this->presentUsers->push($user);
        }

        //dd($users);
    }

    //#[On('echo-presence:chatroom,joining')]
    public function joining($user)
    {
        //dd($this->presentUsers);

        $user = User::find($user['id']);

        $this->presentUsers->push($user); 
    }

    //#[On('echo-presence:chatroom,leaving')]
    public function leaving($user)
    {
        dd($this->presentUsers);

        $userModel = User::find($user['id']);

        $this->presentUsers = $this->presentUsers->filter(function ($value, int $key) use ($userModel) {
            return $value->id != $userModel->id;
        }); 
    } */

    public function render()
    {
        return view('livewire.message-box');
    }
}
