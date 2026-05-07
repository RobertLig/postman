<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SenderDimension extends Model
{
    /** @use HasFactory<\Database\Factories\SenderDimensionFactory> */
    use HasFactory;

    protected $fillable = ['metric_or_imperial', 'length', 'width', 'height'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class);
    }
}
