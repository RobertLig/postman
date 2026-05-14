<?php

namespace App\Livewire\Announcement;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Sender;
use App\Models\Courier;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Validate;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

#[Title('Senders` announcements')]
class ShowAnnouncements extends Component
{
    use WithPagination;

    public string $type = 'sender';

    public $language;

    public bool $drawer = false;

    #[Url(as: 'thing')] //it is a pity that can't use __('thing') to localize query string
    #[Validate('string|max:20')]
    public $thing = ''; //initialize with '' to remove from url query string when input is empty

    #[Url]
    #[Validate('string|max:200')]
    public $description = '';

    #[Url(except: '')]
    #[Validate('string|in:metric,imperial')]
    public $metricOrImperial = ''; //must be initialized to keep it in url on page reloads

    #[Url]
    #[Validate('integer|min:1')]
    public $dimensionLength = ''; //can't be $length name for a property. Alpine.js doesn't accept

    #[Url]
    #[Validate('integer|min:1')]
    public $width = '';

    #[Url]
    #[Validate('integer|min:1')]
    public $height = '';

    #[Url]
    #[Validate('integer|min:1')]
    public $weight = '';

    #[Url]
    #[Validate('date')]
    public $posting_at;
    #[Url]
    #[Validate('date')]
    public $reception_at;

    #[Url]
    #[Validate('string')]
    public $postingPlace = '';

    #[Url]
    #[Validate('string')]
    public $receptionPlace = '';

    public string $metaDescription;

    public function mount(string $type = 'sender')
    {
        $this->type = $type;

        $this->metaDescription = __('Maybe you are going somewhere and you\'d like to drop something off for someone.');

        $this->language = Language::where('code', App::currentLocale())->first();

        //filters
        //$this->metricOrImperial = 'metric';
    }

    protected function modelClass(): string
    {
        return $this->type === 'sender'
            ? Sender::class
            : Courier::class;
    }

    protected function supportsImages(): bool
    {
        return $this->type === 'sender';
    }

    public function changeSuffix()
    {
        $this->dispatch('metric-or-imperial', metricOrImperial: $this->metricOrImperial);
    }

    public function delete($id)
    {
        $modelClass = $this->modelClass();

        $announcement = $modelClass::findOrFail($id);

        $this->authorize('delete', $announcement);

        if ($this->supportsImages()) {
            if ($announcement->library !== [] && $announcement->library->count()) {
                foreach ($announcement->library as $image) {
                    Storage::disk('public')->delete($image['path']); //senders-announcements
                }
            }
        }

        $announcement->delete();
    }

    public function removeFilters()
    {
        $this->thing = '';
        $this->description = '';
        $this->metricOrImperial = '';
        $this->dimensionLength = '';
        $this->width = '';
        $this->height = '';
        $this->weight = '';
        $this->posting_at = '';
        $this->reception_at = '';
        $this->postingPlace = '';
        $this->receptionPlace = '';
    }

    protected function applyFilters(Builder $query): Builder
    {
        return $query
            ->when($this->thing, function (Builder $query, $thing) {
                return $query->whereHas('translations', function (Builder $query) use ($thing) {
                    $query->where([
                        ['thing', 'like', '%' . $thing . '%'],
                        ['lang_id', $this->language->id]
                    ]);
                });
            })

            ->when($this->description, function (Builder $query, $description) {
                return $query->whereHas('translations', function (Builder $query) use ($description) {
                    $query->where([
                        ['description', 'like', '%' . $description . '%'],
                        ['lang_id', $this->language->id]
                    ]);
                });
            })

            ->when($this->weight, function (Builder $query, $weight) {
                return $query->whereHas('weights', function (Builder $query) use ($weight) {
                    $query->where([
                        ['weight', $weight],
                        ['metric_or_imperial', $this->metricOrImperial]
                    ]);
                });
            })

            ->when($this->dimensionLength, function (Builder $query, $dimensionLength) {
                return $query->whereHas('dimensions', function (Builder $query) use ($dimensionLength) {
                    $query->where([
                        ['length', $dimensionLength],
                        ['metric_or_imperial', $this->metricOrImperial]
                    ]);
                });
            })

            ->when($this->width, function (Builder $query, $width) {
                return $query->whereHas('dimensions', function (Builder $query) use ($width) {
                    $query->where([
                        ['width', $width],
                        ['metric_or_imperial', $this->metricOrImperial]
                    ]);
                });
            })

            ->when($this->height, function (Builder $query, $height) {
                return $query->whereHas('dimensions', function (Builder $query) use ($height) {
                    $query->where([
                        ['height', $height],
                        ['metric_or_imperial', $this->metricOrImperial]
                    ]);
                });
            })

            ->when($this->postingPlace, function (Builder $query, $postingPlace) {
                return $query->whereHas('translations', function (Builder $query) use ($postingPlace) {
                    $query->where([
                        ['posting_place', 'like', '%' . $postingPlace . '%'],
                        ['lang_id', $this->language->id]
                    ]);
                });
            })

            ->when($this->receptionPlace, function (Builder $query, $receptionPlace) {
                return $query->whereHas('translations', function (Builder $query) use ($receptionPlace) {
                    $query->where([
                        ['reception_place', 'like', '%' . $receptionPlace . '%'],
                        ['lang_id', $this->language->id]
                    ]);
                });
            })

            ->when($this->posting_at, function (Builder $query, $posting_at) {
                return $query->where('posting_at', $posting_at);
            })

            ->when($this->reception_at, function (Builder $query, $reception_at) {
                return $query->where('reception_at', $reception_at);
            });
    }

    public function render()
    {
        $announcements = $this->applyFilters(
            $this->modelClass()::query()
        )
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view(
            'livewire.announcement.show-announcements',
            compact('announcements')
        );
    }
}
