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

Broadcast::channel(
    'presence-conversation.{conversationId}',

    function ($user, $conversationId) {

        return \App\Models\Conversation::query()

            ->whereKey($conversationId)

            ->whereHas('users', function ($query) use ($user) {

                $query->where('user_id', $user->id);
            })

            ->exists()

            ? [
                'id' => $user->id,
                'name' => $user->name,
            ]

            : false;
    }
);
