<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ShowUsersWithCourierMessages extends Component
{
    use WithPagination;

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
    }

    public function newMessageDeletedNotification()
    {
        //this event listener must be declared to refresh the $users in render() method
    }

    public function render()
    {
        $usersToReceiveMessagesToTheirAnnouncements = User::withWhereHas('couriers', function ($query) {
            return $query->whereHas('messages', function (Builder $query) { //withCount() ?
                return $query->where([
                    ['sender_id', Auth::user()->id]
                ])
                ->orWhere(function (Builder $query) {
                    return $query->where('recipient_id', Auth::user()->id);
                });
            });
        })
        ->whereNot('id', Auth::user()->id)
        ->paginate(10, pageName:'not-my-courier-page');

        return view('livewire.show-users-with-courier-messages', compact('usersToReceiveMessagesToTheirAnnouncements') );
    }
}
