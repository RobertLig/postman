<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\Language;
use Illuminate\Support\Facades\App;

class Courier extends Model
{
    /** @use HasFactory<\Database\Factories\CourierFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'posting_day',
        'posting_year',
        'posting_hour',
        'posting_minute',
        'reception_day',
        'reception_year',
        'reception_hour',
        'reception_minute'
    ];

    // Accessor for meta description
    public function getMetaDescriptionAttribute()
    {
        $language = Language::where('code', App::currentLocale())->first();
        // If content exists, use a summary; otherwise, fall back to the title or a default message
        if (!empty($this->translate($language->id)->description)) {
            return Str::limit(strip_tags($this->translate($language->id)->description), 150);
        }

        // Fall back to the title
        return $this->translate($language->id)->thing;
    }

    /**
     * 
     * @return BelongsTo<User, Courier>
     * 
     * get the user that owns the SenderAnnouncement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CourierTranslation::class); 
    }

    public function translate($langId)
    {
        return $this->translations->where('lang_id', $langId)->first();
    }

    public function weights(): HasMany
    {
        return $this->hasMany(CourierWeight::class);
    }

    public function getWeight($metricOrImperial)
    {
        return $this->weights->where('metric_or_imperial', $metricOrImperial)->first();
    }

    public function dimensions(): HasMany
    {
        return $this->hasMany(CourierDimension::class);
    }

    public function getDimension($metricOrImperial)
    {
        return $this->dimensions->where('metric_or_imperial', $metricOrImperial)->first();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'courier_announcement_id');
    }

    public function initials(): string
    {
        $language = Language::where('code', App::currentLocale())->first();

        $thing = $this->translate($language->id)->thing;

        return Str::of($thing)
            ->explode(' ')
            ->map(fn (string $thing) => Str::of($thing)->substr(0, 1))
            ->implode('');
    }

    public function title(): string
    {
        $language = Language::where('code', App::currentLocale())->first();

        return $this->translate($language->id)->thing;
    }
}
