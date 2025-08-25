<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SenderAnnouncement extends Model
{
    /** @use HasFactory<\Database\Factories\SenderAnnouncementFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_url_1',
        'photo_url_2',
        'photo_url_3',
        'photo_url_4',
        'library',
        'posting_day',
        'posting_year',
        'posting_hour',
        'posting_minute',
        'reception_day',
        'reception_year',
        'reception_hour',
        'reception_minute'
    ];

    protected function casts(): array
    {
        return [
            'library' => AsCollection::class,
        ];
    }

    /**
     * 
     * @return BelongsTo<User, SenderAnnouncement>
     * 
     * get the user that owns the SenderAnnouncement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SenderAnnouncementTranslation::class);
    }

    public function translate($langId)
    {
        return $this->translations->where('lang_id', $langId)->first();
    }
}
