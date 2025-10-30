<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
//use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ShowAllUsers extends Component
{
    public function render()
    {
        $allUsers = User::withCount(['messages' => function (Builder $query) {
            $query->where('sender_announcement_id', null)
                ->where(function (Builder $query) {
                    $query->where('sender_id', Auth::user()->id)
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

        dd($allUsers);

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
