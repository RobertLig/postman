<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SenderAnnouncement;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }

    public function talk(User $user, User $model): bool
    {
        //dd(request()->query('senderannouncement')); //$request->route('senderannouncement')

        /* $senderAnnouncementID = request()->query('senderannouncement'); //id or null

        if(!$senderAnnouncementID)
        {
            return $user->id !== $model->id;
        }
        else
        {
            $senderAnnouncement = SenderAnnouncement::findOrFail($senderAnnouncementID);

                                               //if announcement belongs to eighter of both users
            return $user->id !== $model->id && ($user->id === $senderAnnouncement->user_id || $model->id === $senderAnnouncement->user_id);
        } */

        return $user->id !== $model->id;
    }

    public function talkAboutAnnouncement(User $user, User $model): bool
    {
        $senderAnnouncementID = request()->query('senderannouncement'); //id or null

        if($senderAnnouncementID)
        {
            $senderAnnouncement = SenderAnnouncement::findOrFail($senderAnnouncementID);

            //if announcement belongs to eighter the sender or recipient of the message
            return $user->id === $senderAnnouncement->user_id || $model->id === $senderAnnouncement->user_id;
        }
        else
        {
            return true;
        }
    }

    public function block(User $user, User $model): bool
    {
        return $user->id !== $model->id;

        //

        //return false;
    }
}
