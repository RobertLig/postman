<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Models\SenderAnnouncement;

class SenderAnnouncementChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct(public SenderAnnouncement $senderAnnouncement)
    {
        //dd($this->senderAnnouncement);
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, User $recipient, SenderAnnouncement $senderAnnouncement): array|bool
    {
        return $user->id === $recipient->id && $this->senderAnnouncement->id === $senderAnnouncement->id; 
    }
}
