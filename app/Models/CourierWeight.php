<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourierWeight extends Model
{
    /** @use HasFactory<\Database\Factories\CourierWeightFactory> */
    use HasFactory;

    protected $fillable = ['metric_or_imperial', 'weight'];

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }
}
