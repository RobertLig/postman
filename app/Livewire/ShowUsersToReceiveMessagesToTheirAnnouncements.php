<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ShowUsersToReceiveMessagesToTheirAnnouncements extends Component
{
    use WithPagination;

    public function render()
    {
        $users = User::whereHas('senderAnnouncements', function (Builder $query) {
            $query->whereHas('messages', function (Builder $query) {
                $query->where( [
                    ['sender_id', Auth::user()->id]
                ]);
            });
        })
        ->paginate(10, pageName:'sent-to-announcement-page');

        return view('livewire.show-users-to-receive-messages-to-their-announcements');
    }
}
