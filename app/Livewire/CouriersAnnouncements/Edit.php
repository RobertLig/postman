<?php

namespace App\Livewire\CouriersAnnouncements;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Google\Cloud\Translate\V3\Client\TranslationServiceClient;
use Google\Cloud\Translate\V3\TranslateTextRequest;
use App\Models\Courier;
use App\Models\MonthTranslation;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

#[Title('Edit couriers` announcement')]
class Edit extends Component
{
    public Courier $courier;

    public $language;

    #[Validate('required|string|max:20')]
    public $thing;

    #[Validate('nullable|string|max:200')]
    public $description;

    #[Validate('required|string|in:metric,imperial')]
    public $metricOrImperial;

    #[Validate('nullable|integer|min:1')]
    public $dimensionLength; //can't be $length name for a property. Alpine.js doesn't accept

    #[Validate('nullable|integer|min:1')]
    public $width;

    #[Validate('nullable|integer|min:1')]
    public $height;

    #[Validate('nullable|integer|min:1')]
    public $weight;

    #[Validate('required|integer|between:1,31')]
    public $postingDay;

    #[Validate('required|integer|between:1,31')]
    public $receptionDay;

    public array $dataDay; 
    public $textValuesDay;
    public int $currentDay;
    public int $calDaysInMonth;

    #[Validate('required|string|in:January,February,March,April,May,June,July,August,September,October,November,December,styczeń,luty,marzec,kwiecień,maj,czerwiec,lipiec,sierpień,wrzesień,październik,listopad,grudzień')]
    public $postingMonth;

    #[Validate('required|string|in:January,February,March,April,May,June,July,August,September,October,November,December,styczeń,luty,marzec,kwiecień,maj,czerwiec,lipiec,sierpień,wrzesień,październik,listopad,grudzień')]
    public $receptionMonth;

    public array $dataMonth;
    public $textValuesMonth;
    public string $currentMonth;

    #[Validate('required|integer|min:2024|date_format:Y')]
    public $postingYear; 

    #[Validate('required|integer|min:2024|date_format:Y')]
    public $receptionYear;

    public array $dataYear;
    public $textValuesYear;
    public string $currentYear;

    #[Validate('required|integer|between:0,23')]
    public $postingHour;

    #[Validate('required|integer|between:0,23')]
    public $receptionHour;

    public array $dataHour;
    public $textValuesHour;
    public string $currentHour;

    #[Validate('required|integer|between:0,59')]
    public $postingMinute;

    #[Validate('required|integer|between:0,59')]
    public $receptionMinute;

    public array $dataMinute;
    public $textValuesMinute;
    public string $currentMinute;

    #[Validate('required|string|max:200')]
    public string $postingPlace;

    #[Validate('required|string|max:200|different:postingPlace')]
    public string $receptionPlace;

    public function mount(Courier $courier): void //received from route parameter
    {
        //dd($senderannouncement); //route model minding works!
        $this->courier = $courier;

        $this->language = Language::where('code', App::currentLocale())->first();

        $this->thing = $this->senderannouncement->translate($this->language->id)->thing;

        //$this->files[] = Storage::url('senders-announcements/k4dgerK0P7XvGLcDQb5NVWPpjzJF01x51wkLVQ18.jpg');

        // Load existing library metadata from your model
        $this->library = $this->senderannouncement->library;
 
        // Or ... an empty collection if this component creates a user
        //$this->library = new Collection();

        $this->description = $this->senderannouncement->translate($this->language->id)->description;

        $this->metricOrImperial = 'metric'; //metric | imperial |could store it in database

        $this->dimensionLength = $this->senderannouncement->getDimension($this->metricOrImperial)->length;

        $this->width = $this->senderannouncement->getDimension($this->metricOrImperial)->width;

        $this->height = $this->senderannouncement->getDimension($this->metricOrImperial)->height;

        $this->weight = $this->senderannouncement->getWeight($this->metricOrImperial)->weight;

        $this->postingPlace = $this->senderannouncement->translate($this->language->id)->posting_place;

        $this->receptionPlace = $this->senderannouncement->translate($this->language->id)->reception_place;

        $this->postingDay = $this->senderannouncement->posting_day;

        $this->postingMonth = $this->senderannouncement->translate($this->language->id)->posting_month;

        $this->postingYear = $this->senderannouncement->posting_year;

        $this->postingHour = $this->senderannouncement->posting_hour;

        $this->postingMinute = $this->senderannouncement->posting_minute; 

        $this->receptionDay = $this->senderannouncement->reception_day;

        $this->receptionMonth = $this->senderannouncement->translate($this->language->id)->reception_month;

        $this->receptionYear = $this->senderannouncement->reception_year;

        $this->receptionHour = $this->senderannouncement->reception_hour;

        $this->receptionMinute = $this->senderannouncement->reception_minute;

        //day
        //$this->currentDay = date("j", mktime(0,0,0, date("n"), date("j"), date("Y")));
        $this->currentDay = 1;

        //$this->calDaysInMonth = cal_days_in_month(CAL_GREGORIAN, date("n"), date("Y"));
        $this->calDaysInMonth = 31;

        //must have 9 elements for Carousela component logic. This logic may be wrong because of possibility to increment or decrement into previous or next month
        /*$this->dataDay = [
            $this->currentDay, 
            date("j", mktime(0,0,0, date("n"), date("j") + 1, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 2, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 3, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 4, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 4, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 3, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 2, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 1, date("Y")))
        ]; */

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

    public function render()
    {
        return view('livewire.couriers-announcements.edit');
    }
}
