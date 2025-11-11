<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
//use App\Models\SenderAnnouncement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class ShowUsersToReceiveMessagesToTheirAnnouncements extends Component
{
    use WithPagination;

    //public string $title;

    public function mount()
    {
        //$this->title = __('See users whose ads you have sent messages to');
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

        Log::info('message sent caught in their sender announcement');
    }

    public function newMessageDeletedNotification()
    {
        //this event listener must be declared to refresh the $users in render() method

        Log::info('message deleted caught in their sender announcement');
    }

    public function render()
    {
        //Should Start from SenderAnnouncement for pagination

        $usersToReceiveMessagesToTheirAnnouncements = User::withWhereHas('senderAnnouncements', function ($query) {
            return $query->whereHas('messages', function (Builder $query) { //withCount() ?
                return $query->where([
                    ['sender_id', Auth::user()->id]
                ])
                ->orWhere(function (Builder $query) {
                    return $query->where('recipient_id', Auth::user()->id);
                });
            });
        })
        /* ->orWithWhereHas('courierAnnouncements', function ($query) { //orWithWhereHas doesn't exist. Maybe do separate for couriers?
            return $query->whereHas('messages', function (Builder $query) {
                return $query->where( [
                    ['sender_id', Auth::user()->id]
                ])
                ->orWhere(function (Builder $query) {
                    return $query->where('recipient_id', Auth::user()->id);
                });
            });
        } ) */
        ->whereNot('id', Auth::user()->id)
        ->paginate(10, pageName:'not-my-sender-announcements-page'); //'not-my-sender-announcements-page' | 'sent-to-announcement-page'


        /* $usersToReceiveMessagesToTheirAnnouncements = User::whereHas('senderAnnouncements', function (Builder $query) { //doesn't loads senderAnnouncements relationship
            $query->whereHas('messages', function (Builder $query) {
                $query->where([
                    ['sender_id', Auth::user()->id]
                ])
                ->orWhere(function (Builder $query) {
                    $query->where('recipient_id', Auth::user()->id);
                });
            });
        }) */
        /* ->orWhereHas('courierAnnouncements', function (Builder $query) {
            $query->whereHas('messages', function (Builder $query) {
                $query->where( [
                    ['sender_id', Auth::user()->id]
                ])
                ->orWhere(function (Builder $query) {
                    $query->where('recipient_id', Auth::user()->id);
                });
            });
        }) */
        /* ->whereNot('id', Auth::user()->id)
        ->paginate(10, pageName:'sent-to-announcement-page'); */

        return view('livewire.show-users-to-receive-messages-to-their-announcements', compact('usersToReceiveMessagesToTheirAnnouncements') );
    }
}
