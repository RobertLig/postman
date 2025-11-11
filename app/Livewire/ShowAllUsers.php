<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
//use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ShowAllUsers extends Component
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

        //Log::info('message sent caught in all users');
    }

    public function newMessageDeletedNotification()
    {
        //this event listener must be declared to refresh the $users in render() method

        //Log::info('message deleted caught in all users');
    }

    public function render()
    {
        $allUsers = User::withCount(['messages' => function (Builder $query) {
            $query->where('sender_announcement_id', null)
                ->where('courier_announcement_id', null)
                ->where(function (Builder $query) {
                    $query->where('sender_id', Auth::user()->id) //doesn't work becaouse relationship 'messages' guaranties it to be always queried user
                        ->orWhere('recipient_id', Auth::user()->id);
                });
        }])
            ->orderByDesc('messages_count')
            ->whereNot('id', Auth::user()->id)
            ->paginate(10, pageName:'all-users-page');

        /* $allUsers = User::orderByDesc(
            Message::whereColumn('sender_id', 'users.id')
                ->count()
        )->paginate(10, pageName:'all-users-page'); */

        //dd($allUsers);

        /* $allUsers = User::withWhereHas('messages', function ($query) {
            return $query->where([
                ['sender_id', Auth::user()->id]
            ])
            ->orWhere(function (Builder $query) {
                return $query->where('recipient_id', Auth::user()->id);
            });
        })
        ->paginate(10, pageName:'all-users-page'); */

        return view('livewire.show-all-users', compact('allUsers') );
    }
}
