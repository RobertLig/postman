<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourierTranslation extends Model
{
    /** @use HasFactory<\Database\Factories\CourierTranslationFactory> */
    use HasFactory;

    protected $fillable = ['lang_id', 'thing', 'description', 'posting_place', 'reception_place', 'waypoints'];

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    protected function casts(): array
    {
        return [
            'waypoints' => 'array',
        ];
    }
}
