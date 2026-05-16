<?php

declare(strict_types=1);

namespace App\Livewire\Conversations;

use App\Models\Message;
use Livewire\Component;

class UnreadMessagesCount extends Component
{
    public int $count = 0;

    public function loadCount(): void
    {
        $this->count = Message::query()

            ->whereHas('conversation.users', function ($query) {
                $query->where('user_id', auth()->id());
            })

            ->where('user_id', '!=', auth()->id())

            ->whereNull('read_at')

            // not deleted for receiver
            ->whereNull('deleted_by_receiver_at')

            ->count();
    }

    public function mount(): void
    {
        $this->loadCount();
    }

    public function getListeners(): array
    {
        return [

            // local update after opening conversation
            'message-read' => 'loadCount',

            // realtime incoming messages
            'echo-private:App.Models.User.' . auth()->id() . ',MessageSent'
            => 'loadCount',
        ];
    }

    public function render()
    {
        return view(
            'livewire.conversations.unread-messages-count'
        );
    }
}
