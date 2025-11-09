<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    protected $fillable = [
        'sender_announcement_id',
        'courier_announcement_id',
        'sender_id',
        'recipient_id',
        'message',
        'is_read',
    ];

    public function senderAnnouncement(): BelongsTo
    {
        return $this->belongsTo(SenderAnnouncement::class, 'sender_announcement_id');
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'courier_announcement_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
