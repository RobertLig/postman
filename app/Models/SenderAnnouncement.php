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
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Collection;
//use App\Models\User;

//use App\Policies\SenderAnnouncementPolicy;
//use Illuminate\Database\Eloquent\Attributes\UsePolicy;

//#[UsePolicy(SenderAnnouncementPolicy::class)]
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

    public function weights(): HasMany
    {
        return $this->hasMany(SenderAnnouncementWeight::class);
    }

    public function getWeight($metricOrImperial)
    {
        return $this->weights->where('metric_or_imperial', $metricOrImperial)->first();
    }

    public function dimensions(): HasMany
    {
        return $this->hasMany(SenderAnnouncementDimension::class);
    }

    public function getDimension($metricOrImperial)
    {
        return $this->dimensions->where('metric_or_imperial', $metricOrImperial)->first();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_announcement_id');
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

    public function firstPhoto(): ?string
    {
        return $this->library->first() ? $this->library->first()['url'] : null;
    }

    public function title(): string
    {
        $language = Language::where('code', App::currentLocale())->first();

        return $this->translate($language->id)->thing;
    }

    /* public function messageSenders()
    {
        $users = new Collection();

        $messages =  $this->messages()->where('recipient_id', Auth::user()->id)->distinct()->paginate(10); //->get()

        foreach($messages as $message)
        {
            $users->push(User::findOrFail($message->sender_id));
        }

        //$users = $users->unique();

        return $users;
    } */
}
