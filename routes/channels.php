<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\SenderAnnouncement;
use App\Broadcasting\SenderAnnouncementChannel;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{recipient_id}', function ($user, $recipient_id) {
    return (int) $user->id === (int) $recipient_id; //can't put comment statements inside
});

//Broadcast::channel('chat.{recipient}.{senderAnnouncement}', SenderAnnouncementChannel::class); //doesn't work because of the problem in SenderAnnouncementChannel

/* Broadcast::channel('chat.{recipient}.{senderAnnouncement}', function (User $user, User $recipient, SenderAnnouncement $senderAnnouncement) { //authorization is made on the listener part only
    return (int) $user->id === (int) $recipient->id && $user->senderAnnouncements->contains($senderAnnouncement);
}); */

Broadcast::channel('chat', function() {
    return true; // Always return true for public channels
});

Broadcast::channel('chatroom', function($user) { //'senderannouncement.{sender_announcement_id}', function($user, int $sender_announcement_id 
    return ['id' => $user->id, 'name' => $user->name]; //return $user; //always returns an array, even if returning model
}); 