<?php

declare(strict_types=1);

namespace App\Livewire\Conversations;

use App\Models\Conversation;
use App\Models\Courier;
use App\Models\Sender;
use Livewire\Component;

class ShowConversation extends Component
{
    public function mount(
        string $type,
        int $announcement
    ) {

        $model = match ($type) {
            'sender' => Sender::class,
            'courier' => Courier::class,
        };

        $announcementModel = $model::findOrFail($announcement);

        $owner = $announcementModel->user;

        abort_if(

            auth()->user()->hasBlocked($owner)
                || $owner->hasBlocked(auth()->user()),

            403,

            __('Messaging is unavailable.')
        );

        // prevent messaging yourself
        abort_if(
            $announcementModel->user_id === auth()->id(),
            403
        );

        // existing conversation?
        $conversation = Conversation::query()
            ->where('conversationable_type', $model)
            ->where('conversationable_id', $announcementModel->id)
            ->whereHas('users', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->first();

        // create if missing
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

        return redirect()->route(
            'conversations.show.existing',
            $conversation
        );
    }

    public function render()
    {
        return <<<'HTML'
        <div></div>
        HTML;
    }
}
