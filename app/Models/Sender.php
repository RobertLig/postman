<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

//#[UsePolicy(SenderAnnouncementPolicy::class)]
class Sender extends Model
{
    /** @use HasFactory<\Database\Factories\SenderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'library',
        'posting_at',
        'reception_at'
    ];

    protected function casts(): array
    {
        return [
            'library' => AsCollection::class,
            'posting_at' => 'datetime',
            'reception_at' => 'datetime',
        ];
    }

    public function conversations()
    {
        return $this->morphMany(
            Conversation::class,
            'conversationable'
        );
    }

    protected static function booted(): void
    {
        static::deleting(function ($sender) {

            if (
                $sender->library !== null &&
                count($sender->library)
            ) {
                foreach ($sender->library as $image) {
                    Storage::disk('public')
                        ->delete($image['path']);
                }
            }
        });
    }

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
     * @return BelongsTo<User, Sender>
     * 
     * get the user that owns the Sender
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SenderTranslation::class);
    }

    public function translate(int $langId)
    {
        return $this->translations->where('lang_id', $langId)->first();
    }

    public function weights(): HasMany
    {
        return $this->hasMany(SenderWeight::class);
    }

    public function getWeight($metricOrImperial)
    {
        return $this->weights->where('metric_or_imperial', $metricOrImperial)->first();
    }

    public function dimensions(): HasMany
    {
        return $this->hasMany(SenderDimension::class);
    }

    public function getDimension($metricOrImperial)
    {
        return $this->dimensions->where('metric_or_imperial', $metricOrImperial)->first();
    }

    public function initials(): string
    {
        $language = Language::where('code', App::currentLocale())->first();

        $thing = $this->translate($language->id)->thing;

        return Str::of($thing)
            ->explode(' ')
            ->map(fn(string $thing) => Str::of($thing)->substr(0, 1))
            ->implode('');
    }

    public function firstPhoto(): ?string
    {
        $first = $this->library?->first();

        return $first['url'] ?? null;
    }

    public function title(): string
    {
        $language = Language::where('code', App::currentLocale())->first();

        return $this->translate($language->id)->thing;
    }
}
