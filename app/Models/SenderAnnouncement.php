<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class SenderAnnouncement extends Model
{
    /** @use HasFactory<\Database\Factories\SenderAnnouncementFactory> */
    use HasFactory;

    protected $fillable = [
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
}
