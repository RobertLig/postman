<?php

namespace App\Livewire\Conversations;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;

class ShowConversationExisting extends Component
{
    public Conversation $conversation;

    public string $body = '';

    public function mount(
        Conversation $conversation
    ): void {

        abort_unless(
            $conversation->users()
                ->where('user_id', auth()->id())
                ->exists(),
            403
        );

        $this->conversation = $conversation;
    }

    public function send(): void
    {
        $this->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        Message::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->reset('body');
    }

    public function render()
    {
        return view(
            'livewire.conversations.show-conversation-existing',
            [
                'messages' => $this->conversation
                    ->messages()
                    ->with('user')
                    ->latest()
                    ->get()
                    ->reverse(),
            ]
        );
    }
}
