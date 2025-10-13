<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SenderAnnouncement;
use Illuminate\Support\Facades\Auth;
//use Livewire\Attributes\On;
use Illuminate\Support\Collection; 
use App\Models\User;
use App\Models\Message;

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

        //$this->users = new Collection();

        //sent messages related to announcements to logged in user
        $this->setUsersToSenderAnnouncement();

        

        /* foreach($this->users as $key => $user)
        {
            $senderAnnouncementID = array_keys($user)[0];

            $userModel = $user[$senderAnnouncementID];

            $userID = $userModel->id;

            $count = Message::where('sender_announcement_id', $senderAnnouncementID)
                ->where('sender_id', $userID)
                ->where('is_read', 0)
                ->get()
                ->count(); 

            dd($this->users);
        } */
        

        //$this->users->values()->all();

        //dd($this->users);
    }

    public function setUsersToSenderAnnouncement()
    {
        $this->users = new Collection();

        foreach($this->senderAnnouncements as $senderAnnouncement)
        {
            $messages = $senderAnnouncement->messages;

            foreach($messages as $message)
            {
                if($message->sender_id != Auth::user()->id) //get received messages
                {
                    $user = User::find($message->sender_id);

                    $this->users->push([$senderAnnouncement->id => $user]);
                }
            }
        }

        $this->users = $this->users->unique();

        //set the unread messages for each user in the loop
        $this->users->transform(function (array $item, int $key) {
            $senderAnnouncementID = array_keys( $item)[0];

            $userModel = $item[$senderAnnouncementID];

            $userID = $userModel->id;

            //how many messages of this user for this specific announcement are unread
            $count = Message::where('sender_announcement_id', $senderAnnouncementID)
                ->where('sender_id', $userID)
                ->where('is_read', 0)
                ->get()
                ->count();

            $item['count'] = $count;

            return $item;
        });
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
            //$user = User::find($message['sender_id']);

            //$this->users->push([$message['sender_announcement_id'] => $user]);

            //sender user may be duplicate, but it is still a new message and its number must be added to the appropriate sender user, even to this duplicate user
            $this->setUsersToSenderAnnouncement();

            //dd($this->users);

            //$this->users = $this->users->unique();

            //set the unread messages for each user in the loop
            /* $this->users->transform(function (array $item, int $key) { 
                $senderAnnouncementID = array_keys( $item)[0];

                $userModel = $item[$senderAnnouncementID];

                $userID = $userModel->id;

                $count = Message::where('sender_announcement_id', $senderAnnouncementID)
                    ->where('sender_id', $userID)
                    ->where('is_read', 0)
                    ->get()
                    ->count();

                $item['count'] = $count;

                return $item;
            }); */
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
