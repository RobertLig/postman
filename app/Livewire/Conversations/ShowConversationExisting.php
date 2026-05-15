<?php

declare(strict_types=1);

namespace App\Livewire\Conversations;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageSent;

class ShowConversationExisting extends Component
{
    public Conversation $conversation;

    public string $body = '';

    public array $messages = [];

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

        $this->messages = $this->conversation
            ->messages()
            ->with('user')
            ->latest()
            ->get()
            ->reverse()
            ->map(fn($message) => [
                'id' => $message->id,
                'body' => $message->body,
                'conversation_id' => $message->conversation_id,
                'user_id' => $message->user_id,
                'user_name' => $message->user->name,
                'created_at' => $message->created_at->diffForHumans(),
            ])
            ->toArray();
    }

    public function getListeners(): array
    {
        return [
            "echo-private:conversation.{$this->conversation->id},MessageSent"
            => 'messageReceived',
        ];
    }

    public function messageReceived($event): void
    {
        $this->messages[] = $event;
    }

    public function send(): void
    {
        $this->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->messages[] = [
            'id' => $message->id,
            'body' => $message->body,
            'conversation_id' => $message->conversation_id,
            'user_id' => $message->user_id,
            'user_name' => auth()->user()->name,
            'created_at' => $message->created_at->diffForHumans(),
        ];

        broadcast(new MessageSent($message))->toOthers();

        $this->reset('body');
    }

    public function render()
    {
        return view(
            'livewire.conversations.show-conversation-existing'
        );
    }
}
