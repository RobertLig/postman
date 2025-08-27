<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SenderAnnouncementWeight extends Model
{
    /** @use HasFactory<\Database\Factories\SenderAnnouncementWeightFactory> */
    use HasFactory;

    protected $fillable = ['metric_or_imperial', 'weight'];

    public function senderAnnouncement(): BelongsTo
    {
        return $this->belongsTo(SenderAnnouncement::class);
    }
}
