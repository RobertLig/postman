<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SenderWeight extends Model
{
    /** @use HasFactory<\Database\Factories\SenderWeightFactory> */
    use HasFactory;

    protected $fillable = ['metric_or_imperial', 'weight'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class);
    }
}
