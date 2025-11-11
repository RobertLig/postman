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

        Log::info('message sent caught in my courier announcement');
    }

    public function newMessageDeletedNotification()
    {
        //this event listener must be declared to refresh the $users in render() method

        Log::info('message deleted caught in my courier announcement');
    }

    public function render()
    {
        if($this->couriers->isNotEmpty())
        {
            $users = User::query()
                ->whereHas('messages', function (Builder $query) {
                    $query->where([
                            ['recipient_id', Auth::user()->id]
                        ]) 
                        ->whereBelongsTo($this->couriers, 'courier'); //Auth::user()->couriers
                })
                //->distinct() //no change
                ->paginate(10, pageName:'my-courier-page'); //10

            //dd($users);
        }
        else
        {
            $users = new LengthAwarePaginator([], 2, 1); //empty paginator, will not be needed in the view, only a placeholder
        }

        return view('livewire.show-users-that-sent-message-to-courier', compact('users') );
    }
}
