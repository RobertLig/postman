<?php

declare(strict_types=1);

namespace App\Livewire\Announcement;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Sender;
use App\Models\Courier;
use App\Models\Language;
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

    public Language $language;

    public bool $drawer = false;

    #[Url(as: 'thing')] //it is a pity that can't use __('thing') to localize query string
    #[Validate('string|max:20')]
    public string $thing = ''; //initialize with '' to remove from url query string when input is empty

    #[Url]
    #[Validate('string|max:200')]
    public string $description = '';

    #[Url(except: '')]
    #[Validate('string|in:metric,imperial')]
    public string $metricOrImperial = ''; //must be initialized to keep it in url on page reloads

    #[Url]
    #[Validate('string')]
    public string $postingPlace = '';

    #[Url]
    #[Validate('string')]
    public string $receptionPlace = '';

    #[Url]
    #[Validate('integer|min:1')]
    public string $dimensionLength = ''; //can't be $length name for a property. Alpine.js doesn't accept

    #[Url]
    #[Validate('integer|min:1')]
    public string $width = '';

    #[Url]
    #[Validate('integer|min:1')]
    public string $height = '';

    #[Url]
    #[Validate('integer|min:1')]
    public string $weight = '';

    #[Url]
    #[Validate('date')]
    public string $posting_at = '';

    #[Url]
    #[Validate('date')]
    public string $reception_at = '';

    public string $metaDescription;

    public function mount(string $type = 'sender'): void
    {
        $this->type = $type;

        $this->metaDescription = __('Maybe you are going somewhere and you\'d like to drop something off for someone.');

        $this->language = Language::where('code', App::currentLocale())->first();
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

    public function changeSuffix(): void
    {
        $this->dispatch('metric-or-imperial', metricOrImperial: $this->metricOrImperial);
    }

    public function delete(int $id): void
    {
        $modelClass = $this->modelClass();

        $announcement = $modelClass::findOrFail($id);

        $this->authorize('delete', $announcement);

        $announcement->delete();
    }

    public function removeFilters(): void
    {
        $this->reset([
            'thing',
            'description',
            'metricOrImperial',
            'dimensionLength',
            'width',
            'height',
            'weight',
            'posting_at',
            'reception_at',
            'postingPlace',
            'receptionPlace',
        ]);
    }

    protected function applyFilters(Builder $query): Builder
    {
        return $query
            ->when(
                $this->thing ||
                    $this->description ||
                    $this->postingPlace ||
                    $this->receptionPlace,

                function (Builder $query) {

                    return $query->whereHas('translations', function (Builder $query) {

                        $query->where('lang_id', $this->language->id);

                        $query->when($this->thing, function (Builder $query) {
                            $query->where(
                                'thing',
                                'like',
                                '%' . $this->thing . '%'
                            );
                        });

                        $query->when($this->description, function (Builder $query) {
                            $query->where(
                                'description',
                                'like',
                                '%' . $this->description . '%'
                            );
                        });

                        $query->when($this->postingPlace, function (Builder $query) {
                            $query->where(
                                'posting_place',
                                'like',
                                '%' . $this->postingPlace . '%'
                            );
                        });

                        $query->when($this->receptionPlace, function (Builder $query) {
                            $query->where(
                                'reception_place',
                                'like',
                                '%' . $this->receptionPlace . '%'
                            );
                        });
                    });
                }
            )

            ->when($this->weight, function (Builder $query, $weight) {
                return $query->whereHas('weights', function (Builder $query) use ($weight) {
                    $query->where([
                        ['weight', $weight],
                        ['metric_or_imperial', $this->metricOrImperial]
                    ]);
                });
            })

            ->when(
                $this->dimensionLength ||
                    $this->width ||
                    $this->height,

                function (Builder $query) {

                    return $query->whereHas('dimensions', function (Builder $query) {

                        $query->where(
                            'metric_or_imperial',
                            $this->metricOrImperial
                        );

                        $query->when($this->dimensionLength, function (Builder $query) {
                            $query->where(
                                'length',
                                $this->dimensionLength
                            );
                        });

                        $query->when($this->width, function (Builder $query) {
                            $query->where(
                                'width',
                                $this->width
                            );
                        });

                        $query->when($this->height, function (Builder $query) {
                            $query->where(
                                'height',
                                $this->height
                            );
                        });
                    });
                }
            )

            ->when($this->posting_at, function (Builder $query, $posting_at) {
                return $query->where('posting_at', $posting_at);
            })

            ->when($this->reception_at, function (Builder $query, $reception_at) {
                return $query->where('reception_at', $reception_at);
            });
    }

    public function hasActiveFilters(): bool
    {
        return
            $this->thing ||
            $this->description ||
            $this->metricOrImperial ||
            $this->dimensionLength ||
            $this->width ||
            $this->height ||
            $this->weight ||
            $this->postingPlace ||
            $this->receptionPlace ||
            $this->posting_at ||
            $this->reception_at;
    }

    public function updated($property)
    {
        if ($property !== 'drawer') {
            $this->resetPage();
        }
    }

    public function render()
    {
        $announcements = $this->applyFilters(
            $this->modelClass()::query()
                ->with([
                    'translations',
                    'dimensions',
                    'weights'
                ])
        )
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view(
            'livewire.announcement.show-announcements',
            compact('announcements')
        );
    }
}
