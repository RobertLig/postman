<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SenderAnnouncementTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\SenderAnnouncementTranslationFactory> */
    use HasFactory;

    protected $fillable = ['lang_id', 'thing', 'description', 'posting_place', 'reception_place', 'posting_month', 'reception_month'];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
