<?php

use Livewire\Component;
//use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Sender;
use App\Models\MonthTranslation;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use App\Services\AnnouncementTranslationService;
use App\Services\AnnouncementMeasurementService;

new #[Title('Create senders` announcement')] class extends Component {
    public Sender $sender;

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

    public string $metaDescription;

    protected $listeners = [
        'libraryValidated' => 'saveModelWithImages',
        'library-saved' => 'redirectAfterSave',
    ];

    public function mount(Sender $sender = null): void
    {
        if ($sender) {
            $this->authorize('update', $sender);

            $this->sender = $sender;

            $this->language = Language::where('code', App::currentLocale())->first();

            $this->thing = $this->sender->translate($this->language->id)->thing;

            $this->description = $this->sender->translate($this->language->id)->description;

            $this->dimensionLength = $this->sender->getDimension($this->metricOrImperial)->length;

            $this->width = $this->sender->getDimension($this->metricOrImperial)->width;

            $this->height = $this->sender->getDimension($this->metricOrImperial)->height;

            $this->weight = $this->sender->getWeight($this->metricOrImperial)->weight;

            $this->postingPlace = $this->sender->translate($this->language->id)->posting_place;

            $this->receptionPlace = $this->sender->translate($this->language->id)->reception_place;

            $this->postingDay = $this->sender->posting_day;

            $this->postingMonth = $this->sender->translate($this->language->id)->posting_month;

            $this->postingYear = $this->sender->posting_year;

            $this->postingHour = $this->sender->posting_hour;

            $this->postingMinute = $this->sender->posting_minute;

            $this->receptionDay = $this->sender->reception_day;

            $this->receptionMonth = $this->sender->translate($this->language->id)->reception_month;

            $this->receptionYear = $this->sender->reception_year;

            $this->receptionHour = $this->sender->reception_hour;

            $this->receptionMinute = $this->sender->reception_minute;
        }

        $this->metaDescription = 'Create senders` announcement';

        $this->metricOrImperial = 'metric';

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
        $this->currentMonth = date('n', mktime(0, 0, 0, date('n'), date('j'), date('Y'))) - 1;

        $this->dataMonth = [__(date('F', mktime(0, 0, 0, date('n'), date('j'), date('Y')))), __(date('F', mktime(0, 0, 0, date('n') + 1, date('j'), date('Y')))), __(date('F', mktime(0, 0, 0, date('n') + 2, date('j'), date('Y')))), __(date('F', mktime(0, 0, 0, date('n') + 3, date('j'), date('Y')))), __(date('F', mktime(0, 0, 0, date('n') + 4, date('j'), date('Y')))), __(date('F', mktime(0, 0, 0, date('n') - 4, date('j'), date('Y')))), __(date('F', mktime(0, 0, 0, date('n') - 3, date('j'), date('Y')))), __(date('F', mktime(0, 0, 0, date('n') - 2, date('j'), date('Y')))), __(date('F', mktime(0, 0, 0, date('n') - 1, date('j'), date('Y'))))];

        $this->textValuesMonth = [__('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December')];

        //year
        $this->currentYear = date('Y', mktime(0, 0, 0, date('n'), date('j'), date('Y')));

        $this->dataYear = [date('Y', mktime(0, 0, 0, date('n'), date('j'), date('Y'))), date('Y', mktime(0, 0, 0, date('n'), date('j'), date('Y') + 1)), date('Y', mktime(0, 0, 0, date('n'), date('j'), date('Y') + 2)), date('Y', mktime(0, 0, 0, date('n'), date('j'), date('Y') + 3)), date('Y', mktime(0, 0, 0, date('n'), date('j'), date('Y') + 4)), date('Y', mktime(0, 0, 0, date('n'), date('j'), date('Y') + 15)), date('Y', mktime(0, 0, 0, date('n'), date('j'), date('Y') + 16)), date('Y', mktime(0, 0, 0, date('n'), date('j'), date('Y') + 17)), date('Y', mktime(0, 0, 0, date('n'), date('j'), date('Y') - 1))];

        //hour
        $this->currentHour = date('G', mktime(date('G'), 0, 0, date('n'), date('j'), date('Y')));

        $this->dataHour = [date('G', mktime(date('G'), 0, 0, date('n'), date('j'), date('Y'))), date('G', mktime(date('G') + 1, 0, 0, date('n'), date('j'), date('Y'))), date('G', mktime(date('G') + 2, 0, 0, date('n'), date('j'), date('Y'))), date('G', mktime(date('G') + 3, 0, 0, date('n'), date('j'), date('Y'))), date('G', mktime(date('G') + 4, 0, 0, date('n'), date('j'), date('Y'))), date('G', mktime(date('G') - 4, 0, 0, date('n'), date('j'), date('Y'))), date('G', mktime(date('G') - 3, 0, 0, date('n'), date('j'), date('Y'))), date('G', mktime(date('G') - 2, 0, 0, date('n'), date('j'), date('Y'))), date('G', mktime(date('G') - 1, 0, 0, date('n'), date('j'), date('Y')))];

        //minute
        $this->currentMinute = (int) date('i', mktime(date('G'), date('i'), 0, date('n'), date('j'), date('Y')));

        $this->dataMinute = [date('i', mktime(date('G'), date('i'), 0, date('n'), date('j'), date('Y'))), date('i', mktime(date('G'), date('i') + 1, 0, date('n'), date('j'), date('Y'))), date('i', mktime(date('G'), date('i') + 2, 0, date('n'), date('j'), date('Y'))), date('i', mktime(date('G'), date('i') + 3, 0, date('n'), date('j'), date('Y'))), date('i', mktime(date('G'), date('i') + 4, 0, date('n'), date('j'), date('Y'))), date('i', mktime(date('G'), date('i') - 4, 0, date('n'), date('j'), date('Y'))), date('i', mktime(date('G'), date('i') - 3, 0, date('n'), date('j'), date('Y'))), date('i', mktime(date('G'), date('i') - 2, 0, date('n'), date('j'), date('Y'))), date('i', mktime(date('G'), date('i') - 1, 0, date('n'), date('j'), date('Y')))];
    }

    /*public function setLength($input) //another option for Carousela component
    {
        $this->dimensionLength = $input;

        //$this->validate(); //for live validation
    }*/

    public function changeSuffix()
    {
        $this->dispatch('metric-or-imperial', metricOrImperial: $this->metricOrImperial);
    }

    //component not working. Couldn't reset properties on Alpine with $wire.entangle() during livewire server roundtrip. Issue not solved
    /*public function updatedPostingMonth()
    {
        if($this->postingYear != null && $this->postingMonth != null)
        {
            $monthTranslationModel = MonthTranslation::where('month', $this->postingMonth)->first();

            $calDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthTranslationModel->month_id, $this->postingYear);

            //if($calDaysInMonth != $this->calDaysInMonth)
            //{
                $this->calDaysInMonth = $calDaysInMonth;

                $this->dispatch('updated-posting-month-year', month: $monthTranslationModel->month_id, year: $this->postingYear, calDaysInMonth: $calDaysInMonth); //monthYearCalDays: [$this->postingMonth, $this->postingYear, $this->calDaysInMonth]

                //$this->dispatch('updated-posting-day', calDaysInMonth: $calDaysInMonth);
            //}
        }

        //dd($calDaysInMonth);
    }

    public function updatedReceptionMonth()
    {


        //dd($this->receptionMonth);
    }*/

    public function boot()
    {
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {
                //dates (can't be too many days in a month or posting can't be equal or bigger than reception)
                if ($this->postingDay && $this->postingMonth && $this->postingYear && $this->postingHour && $this->postingMinute && $this->receptionDay && $this->receptionMonth && $this->receptionYear && $this->receptionHour && $this->receptionMinute) {
                    $postingMonthTranslation = MonthTranslation::where('month', $this->postingMonth)->first(); //$postingMonthTranslation->month_id

                    //$dateTimeObj = DateTime::createFromFormat('Y-n-j', $dateTime);

                    $totalPostingDaysAllowed = cal_days_in_month(CAL_GREGORIAN, $postingMonthTranslation->month_id, $this->postingYear);

                    if ($this->postingDay > $totalPostingDaysAllowed) {
                        //!($dateTimeObj && $dateTimeObj->format('Y-n-j') == $dateTime)
                        $validator->errors()->add('postingDay', __('Too many days in this month.'));

                        //dd($validator->errors()->get("postingDay"));
                    }

                    $receptionMonthTranslation = MonthTranslation::where('month', $this->receptionMonth)->first();

                    //$dateTimeObj = DateTime::createFromFormat('Y-n-j', $dateTime);

                    $totalReceptionDaysAllowed = cal_days_in_month(CAL_GREGORIAN, $receptionMonthTranslation->month_id, $this->receptionYear);

                    if ($this->receptionDay > $totalReceptionDaysAllowed) {
                        //!($dateTimeObj && $dateTimeObj->format('Y-n-j') == $dateTime)
                        $validator->errors()->add('receptionDay', __('Too many days in this month.'));

                        //dd($validator->errors()->get("receptionDay"));
                    }

                    $origin = $this->postingYear . '-' . $postingMonthTranslation->month_id . '-' . $this->postingDay . ' ' . $this->postingHour . ':' . $this->postingMinute;

                    $target = $this->receptionYear . '-' . $receptionMonthTranslation->month_id . '-' . $this->receptionDay . ' ' . $this->receptionHour . ':' . $this->receptionMinute;

                    $dateTimestamp1 = strtotime($origin);
                    $dateTimestamp2 = strtotime($target);

                    if ($dateTimestamp1 >= $dateTimestamp2) {
                        $validator->errors()->add('receptionMinute', __('Reception must be later than posting.'));

                        //dd('Reception must be later than posting.');
                    }
                }
            });
        });
    }

    public function save()
    {
        $this->validate();

        $this->dispatch('validateLibrary');
    }

    public function saveModelWithImages()
    {
        if ($this->sender) {
            $this->updateModel();
        } else {
            $this->createModel();
        }
    }

    protected function updateModel()
    {
        //Logic to update model
        //...

        $this->dispatch('updateLibraryModel', modelId: $this->sender->id);
    }

    public function createModel()
    {
        $user = Auth::user();

        $this->sender = Sender::create([
            'user_id' => $user->id,
            'posting_day' => $this->postingDay,
            'posting_year' => $this->postingYear,
            'posting_hour' => $this->postingHour,
            'posting_minute' => $this->postingMinute,
            'reception_day' => $this->receptionDay,
            'reception_year' => $this->receptionYear,
            'reception_hour' => $this->receptionHour,
            'reception_minute' => $this->receptionMinute,
        ]);

        app(AnnouncementTranslationService::class)->createSenderTranslations($this->sender, [
            'thing' => $this->thing,
            'description' => $this->description,
            'posting_place' => $this->postingPlace,
            'reception_place' => $this->receptionPlace,
            'posting_month' => $this->postingMonth,
            'reception_month' => $this->receptionMonth,
        ]);

        app(AnnouncementMeasurementService::class)->createSenderWeights($this->sender, $this->weight, $this->metricOrImperial);

        app(AnnouncementMeasurementService::class)->createSenderDimensions($this->sender, $this->dimensionLength, $this->width, $this->height, $this->metricOrImperial);

        $this->dispatch('updateLibraryModel', modelId: $this->sender->id);
    }

    public function redirectAfterSave()
    {
        $this->redirectRoute('senders-announcements.index');
    }

    public function onLibraryValidationFailed()
    {
        $this->childValid = false;
        // Show error, halt further actions
        //session()->flash('error', 'Image validation failed. Please fix the errors.');
    }
};
