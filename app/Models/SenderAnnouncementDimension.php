<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SenderAnnouncementDimension extends Model
{
    /** @use HasFactory<\Database\Factories\SenderAnnouncementDimensionFactory> */
    use HasFactory;

    protected $fillable = ['metric_or_imperial', 'length', 'width', 'height'];

    public function senderAnnouncement(): BelongsTo
    {
        return $this->belongsTo(SenderAnnouncement::class);
    }
}
