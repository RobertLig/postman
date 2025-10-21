<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SenderAnnouncement;
use Illuminate\Support\Facades\Auth;
//use Livewire\Attributes\On;
//use Illuminate\Support\Collection; 
use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
//use Illuminate\Pagination\LengthAwarePaginator;

class MessageBox extends Component
{
    public $senderAnnouncements; 

    //public Collection $presentUsers; //doesn't work

    //public LengthAwarePaginator $users;

    public function mount() 
    {
        $this->senderAnnouncements = Auth::user()->senderAnnouncements; 

        //$this->presentUsers = new Collection(); //doesn't work

        //sent messages related to announcements to logged in user
        //$this->setUsersToSenderAnnouncement();
    }

    /* public function setUsersToSenderAnnouncement()
    {
        //Another option: Iterate through SenderAnnouncements, get messages for each senderAnnouncement sent to auth user. By each message get to its sender (user).
        //Get unique ( ->distinct() ) users for each senderAnnouncement 

        $this->users = User::query()
            ->whereHas('messages', function (Builder $query) {
                $query->where([
                        ['recipient_id', Auth::user()->id]
                    ])
                    ->whereBelongsTo(Auth::user()->senderAnnouncements, 'senderAnnouncement');
            })->paginate(10); 

        //dd($this->users);
    } */

    public function getListeners()
    {
        $loginID = Auth::user()->id;

        return [
            "echo-private:chat.{$loginID},MessageSent" => 'newChatMessageNotification',
            "echo-private:chat.{$loginID},MessageDeleted" => 'newMessageDeletedNotification'
        ];
    }

    public function newChatMessageNotification($message)
    {
        if($message['sender_announcement_id']) //if this is a message about annnouncement (not needed?)
        { 
            //?
        }
    }

    public function newMessageDeletedNotification()
    {
       //this event listener must be declared to refresh the $users in render() method
    }

    public function getListeners()
    {
        return [
            //"echo-presence:senderAnnouncement,UserEnterAnnouncement" => 'newUsersNotification', //? //"echo-presence:senderannouncement.{sender_announcement_id},UserEnterAnnouncement"
            "echo-presence:senderAnnouncement,here" => 'hereSenderAnnouncement',
            "echo-presence:senderAnnouncement,joining" => 'joiningSenderAnnouncement',
            "echo-presence:senderAnnouncement,leaving" => 'leavingSenderAnnouncement',
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
        $users = User::query()
            ->whereHas('messages', function (Builder $query) {
                $query->where([
                        ['recipient_id', Auth::user()->id]
                    ]) 
                    ->whereBelongsTo(Auth::user()->senderAnnouncements, 'senderAnnouncement');
            })
            //->distinct() //no change
            ->paginate(10); 

        //dd($users);

        return view('livewire.message-box', compact('users') ); 
    }
}
