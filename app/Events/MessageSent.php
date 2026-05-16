<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public Message $message
    ) {}

    public function broadcastOn(): array
    {
        $recipientId = $this->message
            ->conversation
            ->users()
            ->where('user_id', '!=', $this->message->user_id)
            ->first()
            ?->id;

        return [

            new PrivateChannel(
                'conversation.' . $this->message->conversation_id
            ),

            new PrivateChannel(
                'App.Models.User.' . $recipientId
            ),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'body' => $this->message->body,
            'conversation_id' => $this->message->conversation_id,
            'user_id' => $this->message->user_id,
            'user_name' => $this->message->user->name,
            'created_at' => $this->message
                ->created_at
                ->diffForHumans(),
        ];
    }
}
