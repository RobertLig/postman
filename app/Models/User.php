<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
//use App\Notifications\QueueableVerifyEmail; //queue doesn't work
//use App\Notifications\ResetPassword; //queue doesn't work
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'age',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function getAvatar(): ?string
    {
        return $this->avatar ? Storage::url('avatars/'.$this->avatar) : null;
    }

    public function senderAnnouncements(): HasMany
    {
        return $this->hasMany(SenderAnnouncement::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function countSenderAnnouncementMessages($sender_announcement_id): int
    {
        return $this->messages->where('sender_announcement_id', $sender_announcement_id)
            ->where('is_read', 0)
            ->count();
    }

    public function countUserUnreadMessages(): int
    {
        return $this->messages->where('sender_announcement_id', null)
            ->where('recipient_id', Auth::user()->id)
            ->where('is_read', 0)
            ->count();
    }

    public function hasSentMessageToThisAnnouncement($senderAnnouncementID)
    {
        foreach ($this->messages as $message)
        {
            if($message->sender_announcement_id == $senderAnnouncementID)
            {
                return true;
            }
        }

        return false;
    }

    /*public function sendEmailVerificationNotification()
    {
        $this->notify(new QueueableVerifyEmail());
    }*/

    /*public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }*/
}
