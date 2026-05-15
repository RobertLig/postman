<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel(
    'conversation.{conversationId}',
    function ($user, int $conversationId) {

        $conversation = Conversation::find($conversationId);

        if (! $conversation) {
            return false;
        }

        return $conversation
            ->users()
            ->where('user_id', $user->id)
            ->exists();
    }
);
