<?php

declare(strict_types=1);

namespace App\Livewire\Conversations;

use Livewire\Component;

class IndexConversations extends Component
{
    public function render()
    {
        $conversations = auth()
            ->user()
            ->conversations()

            ->with([
                'users',
                'latestMessage.user',
                'conversationable',
            ])

            ->withCount([
                'messages as unread_count' => function ($query) {

                    $query
                        ->where('user_id', '!=', auth()->id())

                        ->whereNull('read_at')

                        ->whereNull('deleted_by_receiver_at')

                        ->whereNull('deleted_by_sender_at');
                }
            ])

            ->withMax('messages', 'created_at')

            ->orderByDesc('messages_max_created_at')

            ->get();

        return view(
            'livewire.conversations.index-conversations',
            [
                'conversations' => $conversations,
            ]
        );
    }
}
