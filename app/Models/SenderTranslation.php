<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SenderTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\SenderTranslationFactory> */
    use HasFactory;

    protected $fillable = ['lang_id', 'thing', 'description', 'posting_place', 'reception_place', 'waypoint_labels'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class);
    }

    protected function casts(): array
    {
        return [
            'waypoint_labels' => 'array',
        ];
    }
}
