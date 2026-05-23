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
use Illuminate\Database\Eloquent\Casts\AsCollection;

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
        'gender',
        'timezone',
        'blocked',
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
            'blocked' => AsCollection::class,
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {

            // Delete avatar
            if (
                $user->avatar &&
                Storage::disk('public')->exists($user->avatar)
            ) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Delete related models THROUGH Eloquent
            $user->senders->each->delete();

            //$user->couriers->each->delete();
        });
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function getAvatar(): ?string
    {
        return $this->avatar ? Storage::disk('public')->url($this->avatar) : null;
    }

    public function senders(): HasMany
    {
        return $this->hasMany(Sender::class);
    }

    public function couriers(): HasMany
    {
        return $this->hasMany(Courier::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)
            ->withPivot('last_read_at')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function blockedUsers()
    {
        return $this->belongsToMany(
            User::class,
            'blocks',
            'blocker_id',
            'blocked_id'
        );
    }

    public function blockedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'blocks',
            'blocked_id',
            'blocker_id'
        );
    }

    public function hasBlocked(User $user): bool
    {
        return $this->blockedUsers()
            ->where('blocked_id', $user->id)
            ->exists();
    }

    public function isBlockedBy(User $user): bool
    {
        return $this->blockedByUsers()
            ->where('blocker_id', $user->id)
            ->exists();
    }

    public function cannotMessage(User $user): bool
    {
        return $this->hasBlocked($user)
            || $this->isBlockedBy($user);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)
            ->latestOfMany();
    }

    public function unreadConversations()
    {
        return $this->conversations()
            ->whereHas('messages', function ($query) {
                $query->whereNull('read_at')
                    ->where('user_id', '!=', $this->id);
            });
    }
}
