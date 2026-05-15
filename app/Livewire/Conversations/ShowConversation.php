<?php

namespace App\Livewire\Conversations;

use App\Models\Conversation;
use App\Models\Courier;
use App\Models\Message;
use App\Models\Sender;
use Livewire\Component;

class ShowConversation extends Component
{
    public Conversation $conversation;

    public string $body = '';

    public function mount(
        ?string $type = null,
        $announcement = null,
        ?Conversation $conversation = null
    ): void {

        // Open existing conversation from inbox
        if ($conversation) {

            abort_unless(
                $conversation->users()
                    ->where('user_id', auth()->id())
                    ->exists(),
                403
            );

            $this->conversation = $conversation;

            return;
        }

        // Open/create from announcement page
        $model = match ($type) {
            'sender' => Sender::class,
            'courier' => Courier::class,
        };

        $announcementModel = $model::findOrFail($announcement);

        abort_if(
            $announcementModel->user_id === auth()->id(),
            403
        );

        $conversation = Conversation::query()
            ->where('conversationable_type', $model)
            ->where('conversationable_id', $announcementModel->id)
            ->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->first();

        if (! $conversation) {

            $conversation = Conversation::create([
                'conversationable_type' => $model,
                'conversationable_id' => $announcementModel->id,
                'created_by' => auth()->id(),
            ]);

            $conversation->users()->attach([
                auth()->id(),
                $announcementModel->user_id,
            ]);
        }

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
        return view('livewire.conversations.show-conversation', [
            'messages' => $this->conversation
                ->messages()
                ->with('user')
                ->latest()
                ->get()
                ->reverse(),
        ]);
    }
}
