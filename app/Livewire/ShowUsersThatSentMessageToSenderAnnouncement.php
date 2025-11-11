<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ShowUsersThatSentMessageToSenderAnnouncement extends Component
{
    use WithPagination;

    public Collection $senderAnnouncements;

    public function mount() 
    {
        $this->senderAnnouncements = Auth::user()->senderAnnouncements; 
    }

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
        //this event listener must be declared to refresh the $users in render() method

        Log::info('message sent caught in my sender announcement');
    }

    public function newMessageDeletedNotification()
    {
        //this event listener must be declared to refresh the $users in render() method

        Log::info('message deleted caught in my sender announcement');
    }

    public function render()
    {
        if($this->senderAnnouncements->isNotEmpty())
        {
            $users = User::query()
                ->whereHas('messages', function (Builder $query) {
                    $query->where([
                            ['recipient_id', Auth::user()->id]
                        ]) 
                        ->whereBelongsTo($this->senderAnnouncements, 'senderAnnouncement'); //Auth::user()->senderAnnouncements
                })
                //->distinct() //no change
                ->paginate(10, pageName:'my-sender-announcements-page'); //'my-sender-announcements-page' | 'sender-announcement-page' | __() sometimes jumps back to the previous page on polish language adds adds double query string

        }
        else
        {
            $users = new LengthAwarePaginator([], 2, 1); //empty paginator, will not be needed in the view, only a placeholder
        }

        return view('livewire.show-users-that-sent-message-to-sender-announcement', compact('users') );
    }
}
