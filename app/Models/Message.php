<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'body',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'deleted_by_sender_at' => 'datetime',
            'deleted_by_receiver_at' => 'datetime',
        ];
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
