<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Models\SenderAnnouncement;
use App\Broadcasting\SenderAnnouncementChannel;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{recipient_id}', function ($user, $recipient_id) {
    return (int) $user->id === (int) $recipient_id; 
});

//Broadcast::channel('chat.{recipient}.{senderAnnouncement}', SenderAnnouncementChannel::class); //doesn't work because of the problem in SenderAnnouncementChannel

/* Broadcast::channel('chat.{recipient}.{senderAnnouncement}', function (User $user, User $recipient, SenderAnnouncement $senderAnnouncement) { //authorization is made on the listener part only
    return (int) $user->id === (int) $recipient->id && $user->senderAnnouncements->contains($senderAnnouncement);
}); */

Broadcast::channel('chat', function() {
    return true; 
});

Broadcast::channel('chatroom.{senderAnnouncement}', function($user, int $senderAnnouncement) { 
    return ['id' => $user->id, 'name' => $user->name, 'senderAnnouncementID' => $senderAnnouncement]; 
}); 

Broadcast::channel('publicChatroom', function($user) { 
    return ['id' => $user->id, 'name' => $user->name]; 
}); 

Broadcast::channel('senderAnnouncement.{senderAnnouncementID}', function ($user, int $senderAnnouncementID) {
    return ['id' => $user->id, 'name' => $user->name, 'senderAnnouncementID' => $senderAnnouncementID]; 
}); 