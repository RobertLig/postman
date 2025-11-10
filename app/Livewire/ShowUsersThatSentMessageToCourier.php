<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ShowUsersThatSentMessageToCourier extends Component
{
    use WithPagination;

    public Collection $couriers;

    public function mount() 
    {
        $this->couriers = Auth::user()->couriers; 
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
    }

    public function newMessageDeletedNotification()
    {
        //this event listener must be declared to refresh the $users in render() method
    }

    public function render()
    {
        $users = User::query()
            ->whereHas('messages', function (Builder $query) {
                $query->where([
                        ['recipient_id', Auth::user()->id]
                    ]) 
                    ->whereBelongsTo(Auth::user()->couriers, 'courier');
            })
            //->distinct() //no change
            ->paginate(10, pageName:'my-courier-page'); //10

        return view('livewire.show-users-that-sent-message-to-courier', compact('users') );
    }
}
