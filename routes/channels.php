<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{recipient_id}', function ($user, $recipient_id) {
    return (int) $user->id === (int) $recipient_id;
});
