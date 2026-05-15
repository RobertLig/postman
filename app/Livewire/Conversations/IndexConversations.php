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
            ->latest('updated_at')
            ->get();

        return view(
            'livewire.conversations.index-conversations',
            [
                'conversations' => $conversations,
            ]
        );
    }
}
