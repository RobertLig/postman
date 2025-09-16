<?php

namespace App\Livewire\SendersAnnouncements;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\SenderAnnouncement;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;
use Mary\Traits\WithMediaSync;
use Livewire\WithPagination;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Validate;

use Illuminate\Database\Eloquent\Builder;


#[Title('Senders` announcements')]
class ShowAnnouncements extends Component
{
    use WithMediaSync, WithPagination;

    //public SenderAnnouncement $senderAnnouncement;

    public $language;

    public bool $drawer = false;

    #[Validate('string|max:20')]
    public $thing;

    #[Validate('string|max:200')]
    public $description;

    #[Validate('string|in:metric,imperial')]
    public $metricOrImperial;

    #[Validate('integer|min:1')]
    public $dimensionLength; //can't be $length name for a property. Alpine.js doesn't accept

    #[Validate('integer|min:1')]
    public $width;

    #[Validate('integer|min:1')]
    public $height;

    #[Validate('integer|min:1')]
    public $weight;

    #[Validate('integer|between:1,31')]
    public $postingDay;

    #[Validate('integer|between:1,31')]
    public $receptionDay;

    public array $dataDay; 
    public $textValuesDay;
    public int $currentDay;
    public int $calDaysInMonth;

    #[Validate('string|in:January,February,March,April,May,June,July,August,September,October,November,December,styczeń,luty,marzec,kwiecień,maj,czerwiec,lipiec,sierpień,wrzesień,październik,listopad,grudzień')]
    public $postingMonth;

    #[Validate('string|in:January,February,March,April,May,June,July,August,September,October,November,December,styczeń,luty,marzec,kwiecień,maj,czerwiec,lipiec,sierpień,wrzesień,październik,listopad,grudzień')]
    public $receptionMonth;

    public array $dataMonth;
    public $textValuesMonth;
    public string $currentMonth;

    #[Validate('integer|min:2024|date_format:Y')]
    public $postingYear; 

    #[Validate('integer|min:2024|date_format:Y')]
    public $receptionYear;

    public array $dataYear;
    public $textValuesYear;
    public string $currentYear;

    #[Validate('integer|between:0,23')]
    public $postingHour;

    #[Validate('integer|between:0,23')]
    public $receptionHour;

    public array $dataHour;
    public $textValuesHour;
    public string $currentHour;

    #[Validate('integer|between:0,59')]
    public $postingMinute;

    #[Validate('integer|between:0,59')]
    public $receptionMinute;

    public array $dataMinute;
    public $textValuesMinute;
    public string $currentMinute;

    #[Validate('string')]
    public $postingPlace;

    #[Validate('string')]
    public $receptionPlace;

    public function mount() //SenderAnnouncement $senderAnnouncement
    {
        //$this->senderAnnouncement = $senderAnnouncement;

        $this->language = Language::where('code', App::currentLocale())->first();

        //filters
        $this->metricOrImperial = 'metric';

        //day
        $this->currentDay = 1;

        $this->calDaysInMonth = 31;

        $this->dataDay = [1, 2, 3, 4, 5, 28, 29, 30, 31];

        //month
        $this->currentMonth = date("n", mktime(0,0,0, date("n"), date("j"), date("Y"))) - 1;

        $this->dataMonth = [
            __( date("F", mktime(0,0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") + 1, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") + 2, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") + 3, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") + 4, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") - 4, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") - 3, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") - 2, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") - 1, date("j"), date("Y"))) )
        ]; 

        $this->textValuesMonth = [ __('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December')];

        //year
        $this->currentYear = date("Y", mktime(0,0,0, date("n"), date("j"), date("Y")));

        $this->dataYear = [
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y"))), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 1)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 2)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 3)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 4)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 15)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 16)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 17)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") - 1))
        ]; 

        //hour
        $this->currentHour = date("G", mktime(date("G"),0,0, date("n"), date("j"), date("Y")));

        $this->dataHour = [
            date("G", mktime(date("G"),0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") + 1,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") + 2,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") + 3,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") + 4,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") - 4,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") - 3,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") - 2,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") - 1,0,0, date("n"), date("j"), date("Y")))
        ];

        //minute
        $this->currentMinute = (int)date("i", mktime(date("G"),date("i"),0, date("n"), date("j"), date("Y")));

        $this->dataMinute = [
            date("i", mktime(date("G"),date("i"),0, date("n"), date("j"), date("Y"))) , 
            date("i", mktime(date("G"),date("i") + 1,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") + 2,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") + 3,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") + 4,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") - 4,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") - 3,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") - 2,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") - 1,0, date("n"), date("j"), date("Y")))
        ];
    }

    public function changeSuffix()
    {
        $this->dispatch('metric-or-imperial', metricOrImperial: $this->metricOrImperial);
    }

    public function delete($id)
    {
        //dd($id);

        $senderannouncement = SenderAnnouncement::find($id);
 
        $this->authorize('delete', $senderannouncement); 

        //delete files of the announcement
        if($senderannouncement->library->count())
        {
            foreach($senderannouncement->library as $image)
            {
                Storage::disk('senders-announcements')->delete($image['path']);
            }
        }
 
        $senderannouncement->delete();
    }

    public function render()
    {
        //$postingDay = 7; //test
        //$thing = 'th';
        /* if($this->postingHour !== null)
        {
            dd($this->postingHour);
        } */   

        $senderAnnouncements = SenderAnnouncement::query()
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
            ->when($this->postingMonth, function (Builder $query, $postingMonth) {
                return $query->whereHas('translations', function (Builder $query) use ($postingMonth) {
                    $query->where([
                        ['posting_month', 'like', '%' . $postingMonth . '%'],
                        ['lang_id', $this->language->id]
                    ]);
                });
            })
            ->when($this->postingDay, function (Builder $query, $postingDay) {
                return $query->where('posting_day', $postingDay);
            })
            ->when($this->postingYear, function (Builder $query, $postingYear) {
                return $query->where('posting_year', $postingYear);
            })
            ->when($this->postingHour, function (Builder $query, $postingHour) {

                return $query->where('posting_hour', $postingHour);

            }, function (Builder $query, $postingHour) {

                return $postingHour === 0 ? $query->where('posting_hour', $postingHour) : $query;
            })
            ->when($this->postingMinute, function (Builder $query, $postingMinute) {

                return $query->where('posting_minute', $postingMinute);

            }, function (Builder $query, $postingMinute) {

                return $postingMinute === 0 ? $query->where('posting_minute', $postingMinute) : $query;
            })
            ->paginate(10);

        /* $senderAnnouncements = SenderAnnouncement::where([['posting_day', '=', 7]]) //[['posting_day', '=', 7]]
            //->orderBy('id', 'DESC')
            ->whereHas('translations', function (Builder $query) { // use ($fairuse)
                $query->where([
                    ['thing', 'like', '%' . 'th' . '%'],
                    ['lang_id', $this->language->id],
                ]); //works with where(null) for no filters
            }) 
            ->paginate(10); */ //SenderAnnouncement::orderBy('id', 'DESC')->paginate(10) | SenderAnnouncement::where('thing', 'like', '%' . 'guitar' . '%')->orderBy('id', 'DESC')->paginate(10) | SenderAnnouncement::all()
        
        

        return view('livewire.senders-announcements.show-announcements', compact('senderAnnouncements'));
    }
}
