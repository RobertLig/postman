<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

//use function Ramsey\Uuid\v1; //I don't know when this was declared and what it does

class SenderAnnouncementPresence extends Component
{
    use WithPagination;

    public $senderAnnouncements;

    public $ids;

    public $userPresentOnSenderAnnouncement;

    public function mount() 
    {
        $this->senderAnnouncements = Auth::user()->senderAnnouncements; 

        $this->ids = [];

        $this->userPresentOnSenderAnnouncement = [];
    }

    public function getListeners()
    {
        //create dynamic channels for each SenderAnnouncement
        foreach($this->senderAnnouncements as $senderAnnouncement)
        {
            $array["echo-presence:senderAnnouncement.{$senderAnnouncement->id},here"] = 'here'; 
            $array["echo-presence:senderAnnouncement.{$senderAnnouncement->id},joining"] = 'joining'; 
            $array["echo-presence:senderAnnouncement.{$senderAnnouncement->id},leaving"] = 'leaving'; 
        }

        return $array;
    }

    /* public function newUsersNotification()
    {
        //dd("I am on show announcement page");
    } */

    //#[On('echo-presence:chatroom,here')]
    public function here($users)
    {
        Log::info('All presentUsers message box: {users}', ['users' => $users]);

        foreach($users as $user)
        {
            if($user['id'] != Auth::user()->id)
            {
                $this->ids[] = $user['id'];

                $this->userPresentOnSenderAnnouncement[ $user['id'] ] = $user['senderAnnouncementID']; //theoretically user can't be at two different announcements at the same time
            }
        } 
    }

    //#[On('echo-presence:chatroom,joining')]
    public function joining($user)
    {
        Log::info('Joining presentUsers message box: {user}', ['user' => $user]);

        $this->ids[] = $user['id']; //may be added two times: once after here() and second time here (problem?)

        $this->userPresentOnSenderAnnouncement[ $user['id'] ] = $user['senderAnnouncementID']; 
    }

    //#[On('echo-presence:chatroom,leaving')]
    public function leaving($user)
    {
        Log::info('Leaving presentUsers message box: {user}', ['user' => $user]);

        unset( $this->userPresentOnSenderAnnouncement[ $user['id'] ] );

        $key = array_search($user['id'], $this->ids);

        unset($this->ids[$key]); 
    } 

    /* public function updatedPage($page)
    {
        // Runs after the page is updated for this component... (for pagination). Not needed
        //dd($page);
    } */

    public function render()
    {
        //users viewing announcements

        //$this->ids = [255, 253, 252, 251, 250, 249, 248, 247, 246, 245, 244, 243, 242, 241, 240]; //test

        /* $this->userPresentOnSenderAnnouncement = [255=>434, 253=>434, 252=>434, 251=>434, 250=>434, 249=>434, 248=>434, 247=>434, 246=>434, 245=>434, 
            244=>433, 243=>433, 242=>433, 241=>433, 240=>433]; */ //test

        $paginatedUsers = User::query()
            ->whereIn('id', $this->ids)
            ->paginate(10); //10

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

        return view('livewire.sender-announcement-presence', compact('presentUsersTransformedAndPaginated') );
    }
}
