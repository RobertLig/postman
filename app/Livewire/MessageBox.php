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
use Illuminate\Support\Facades\Log;

use function Ramsey\Uuid\v1;

class MessageBox extends Component
{
    public $senderAnnouncements; 

    public $presentUsers; //doesn't work | /public Collection $presentUsers

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

        $array = [
            "echo-private:chat.{$loginID},MessageSent" => 'newChatMessageNotification',
            "echo-private:chat.{$loginID},MessageDeleted" => 'newMessageDeletedNotification'
        ];

        //create dynamic channels for each SenderAnnouncement
        foreach($this->senderAnnouncements as $senderAnnouncement)
        {
            $array["echo-presence:senderAnnouncement.{$senderAnnouncement->id},here"] = 'here';
            $array["echo-presence:senderAnnouncement.{$senderAnnouncement->id},joining"] = 'joining';
            $array["echo-presence:senderAnnouncement.{$senderAnnouncement->id},leaving"] = 'leaving';
        }

        return $array;

        /* return [
            "echo-private:chat.{$loginID},MessageSent" => 'newChatMessageNotification',
            "echo-private:chat.{$loginID},MessageDeleted" => 'newMessageDeletedNotification',
            //"echo-presence:senderAnnouncement,UserEnterAnnouncement" => 'newUsersNotification', //? //"echo-presence:senderannouncement.{sender_announcement_id},UserEnterAnnouncement"
            "echo-presence:senderAnnouncement,here" => 'here',
            "echo-presence:senderAnnouncement,joining" => 'joining',
            "echo-presence:senderAnnouncement,leaving" => 'leaving',
        ]; */
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

    public function newUsersNotification()
    {
        //dd("I am on show announcement page");
    }

    //#[On('echo-presence:chatroom,here')]
    public function here($users)
    {
        Log::info('All presentUsers: {users}', ['users' => $users]);

        foreach($users as $user)
        {
            $ids[] = $user['id'];
            //$this->presentUsers->push($user);
        } 

        User::query()
            ->whereIn('id', $ids)
            ->paginate(10);

        //dd($users);
    }

    //#[On('echo-presence:chatroom,joining')]
    public function joining($user)
    {
        //dd($this->presentUsers);

        Log::info('Joining presentUsers: {user}', ['user' => $user]);

        /* $user = User::find($user['id']);

        $this->presentUsers->push($user); */ 
    }

    //#[On('echo-presence:chatroom,leaving')]
    public function leaving($user)
    {
        Log::info('Leaving presentUsers: {user}', ['user' => $user]);

        //dd($this->presentUsers);

        /* $userModel = User::find($user['id']);

        $this->presentUsers = $this->presentUsers->filter(function ($value, int $key) use ($userModel) { 
            return $value->id != $userModel->id;
        }); */
    } 

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
