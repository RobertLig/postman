<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Models\SenderAnnouncement;
//use Illuminate\Support\Facades\Log;

class SenderAnnouncementChannel 
{
    /**
     * Create a new channel instance.
     */
    public function __construct(public SenderAnnouncement $senderAnnouncement)
    {
        //dd($this->senderAnnouncement->id);
        //Log::info('Showing senderAnnouncementID in construct: {senderAnnouncementID}', ['senderAnnouncementID' => $this->senderAnnouncement->id]);
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, User $recipient, SenderAnnouncement $senderAnnouncement): array|bool 
    {
        //dd($sender_announcement_id); //$recipient_id,

        //Log::info('Showing senderAnnouncementID: {senderAnnouncementID}', ['senderAnnouncementID' => (int) $senderAnnouncement->id]);

        //construct is ivoked twice (why?). The second time sets $senderAnnouncement to null, making it unable to compare to senderAnnouncement from route parameter
        return (int) $user->id === (int) $recipient->id && (int) $this->senderAnnouncement->id === (int) $senderAnnouncement->id; 

        //return true;
    }
}
