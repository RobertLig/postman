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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

use function Ramsey\Uuid\v1;

class MessageBox extends Component
{
    use WithPagination;

    public $senderAnnouncements; 

    public $presentUsers; //doesn't work | /public Collection $presentUsers

    //public LengthAwarePaginator $users;

    public $ids;

    public $userPresentOnSenderAnnouncement;

    public function mount() 
    {
        $this->senderAnnouncements = Auth::user()->senderAnnouncements; 

        $this->ids = [];

        $this->userPresentOnSenderAnnouncement = [];

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

        /* foreach($users as $user)
        {
            if($user['id'] != Auth::user()->id)
            {
                $this->ids[] = $user['id'];

                $this->userPresentOnSenderAnnouncement[ $user['id'] ] = $user['senderAnnouncementID']; //theoretically user can't be at two different announcements at the same time
            }
        } */
    }

    //#[On('echo-presence:chatroom,joining')]
    public function joining($user)
    {
        Log::info('Joining presentUsers: {user}', ['user' => $user]);

        /* $this->ids[] = $user['id']; //may be added two times: once after here() and second time here (problem?)

        $this->userPresentOnSenderAnnouncement[ $user['id'] ] = $user['senderAnnouncementID']; */
    }

    //#[On('echo-presence:chatroom,leaving')]
    public function leaving($user)
    {
        Log::info('Leaving presentUsers: {user}', ['user' => $user]);

        /* unset( $this->userPresentOnSenderAnnouncement[ $user['id'] ] );

        $key = array_search($user['id'], $this->ids);

        unset($this->ids[$key]); */
    } 

    public function updatedPage($page)
    {
        // Runs after the page is updated for this component...
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

        //users viewing announcements

        $this->ids = [255, 253, 252, 251, 250, 249, 248, 247, 246, 245]; //test

        $this->userPresentOnSenderAnnouncement = [255=>434, 253=>434, 252=>434, 251=>434, 250=>434, 249=>434, 248=>434, 247=>434, 246=>434, 245=>434]; //test

        $paginatedUsers = User::query()
            ->whereIn('id', $this->ids)
            ->paginate(1); //10

        $itemsTransformed = $paginatedUsers
            ->getCollection()
            ->map(function($item) {
                return [
                    $this->userPresentOnSenderAnnouncement[$item->id], $item, //$item->id
                ];
            }); //->toArray()

        /* $itemsTransformed = tap($paginatedUsers,function($paginatedInstance){ //alternative
            return $paginatedInstance->getCollection()->transform(function ($value) {
                return $value;
            });
        }); */

        $presentUsersTransformedAndPaginated = new LengthAwarePaginator(
            $itemsTransformed,
            $paginatedUsers->total(),
            $paginatedUsers->perPage(),
            $paginatedUsers->currentPage(), 
            /* [
                'path' => url()->current(), //may be needed
                //'query' => [
                    //'page' => $paginatedUsers->currentPage()
                //] 
            ] */
        );

        //dd($presentUsersTransformedAndPaginated);

        return view('livewire.message-box', compact('users', 'presentUsersTransformedAndPaginated') ); 

        /* return view('livewire.message-box', [
            'users' => User::query()
                ->whereHas('messages', function (Builder $query) {
                    $query->where([
                            ['recipient_id', Auth::user()->id]
                        ]) 
                        ->whereBelongsTo(Auth::user()->senderAnnouncements, 'senderAnnouncement');
                })
                //->distinct() //no change
                ->paginate(10),

            'presentUsersTransformedAndPaginated' => $presentUsersTransformedAndPaginated
        ]); */
    }
}
