<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourierDimension extends Model
{
    /** @use HasFactory<\Database\Factories\CourierDimensionFactory> */
    use HasFactory;

    protected $fillable = ['metric_or_imperial', 'length', 'width', 'height'];

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }
}
