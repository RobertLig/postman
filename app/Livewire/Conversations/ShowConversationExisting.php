<?php

declare(strict_types=1);

namespace App\Livewire\Conversations;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageSent;
use Mary\Traits\Toast;

class ShowConversationExisting extends Component
{
    use Toast;

    public Conversation $conversation;

    public string $body = '';

    public array $messages = [];

    public bool $showTyping = false;

    public ?string $typingUser = null;

    public $otherUser;

    public bool $otherUserOnline = false;

    public function mount(
        Conversation $conversation
    ): void {

        abort_unless(
            $conversation->users()
                ->where('user_id', auth()->id())
                ->exists(),
            403
        );

        $this->conversation
            ->messages()
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        $this->dispatch('message-read');

        $this->conversation = $conversation;

        $this->otherUser = $this->conversation
            ->users()
            ->where('user_id', '!=', auth()->id())
            ->first();

        $this->loadMessages();
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

    public function showTypingIndicator(
        string $userName
    ): void {

        $this->typingUser = $userName;

        $this->showTyping = true;
    }

    public function hideTypingIndicator(): void
    {
        $this->showTyping = false;
    }

    public function send(): void
    {
        $otherUser = $this->conversation
            ->users()
            ->where('user_id', '!=', auth()->id())
            ->first();

        abort_if(

            auth()->user()->hasBlocked($otherUser)
                || $otherUser->hasBlocked(auth()->user()),

            403,

            __('Messaging is unavailable.')
        );

        $this->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $this->loadMessages();

        broadcast(new MessageSent($message))->toOthers();

        $this->reset('body');
    }

    public function deleteMessage(
        int $messageId
    ): void {

        $message = Message::findOrFail($messageId);

        abort_unless(
            $message->conversation
                ->users()
                ->where('user_id', auth()->id())
                ->exists(),
            403
        );

        if ($message->user_id == auth()->id()) {

            $message->deleted_by_sender_at = now();
        } else {

            $message->deleted_by_receiver_at = now();
        }

        $message->save();

        if (
            $message->deleted_by_sender_at &&
            $message->deleted_by_receiver_at
        ) {
            $message->delete();
        }

        $this->loadMessages();
    }

    protected function loadMessages(): void
    {
        $this->messages = $this->conversation
            ->messages()

            ->where(function ($query) {

                $query

                    // own messages not deleted by sender
                    ->where(function ($q) {

                        $q->where('user_id', auth()->id())
                            ->whereNull('deleted_by_sender_at');
                    })

                    // received messages not deleted by receiver
                    ->orWhere(function ($q) {

                        $q->where('user_id', '!=', auth()->id())
                            ->whereNull('deleted_by_receiver_at');
                    });
            })

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

    public function blockUser(): void
    {
        auth()->user()
            ->blockedUsers()
            ->syncWithoutDetaching([
                $this->otherUser->id,
            ]);

        $this->success(
            __('User blocked.'),
            redirectTo: route('conversations.index'),
            position: 'toast-bottom'
        );
    }

    public function unblockUser(): void
    {
        auth()->user()
            ->blockedUsers()
            ->detach($this->otherUser->id);

        $this->success(
            __('User unblocked.'),
            position: 'toast-bottom'
        );
    }

    public function userOnline(array $users): void
    {
        $this->otherUserOnline = collect($users)
            ->contains(
                fn($user) =>
                (int) $user['id']
                    === (int) $this->otherUser->id
            );
    }

    public function userJoined(array $user): void
    {
        if ((int) $user['id'] === (int) $this->otherUser->id) {

            $this->otherUserOnline = true;
        }
    }

    public function userLeft(array $user): void
    {
        if ((int) $user['id'] === (int) $this->otherUser->id) {

            $this->otherUserOnline = false;
        }
    }

    public function render()
    {
        return view(
            'livewire.conversations.show-conversation-existing'
        );
    }
}
