<?php

namespace App\Livewire\SendersAnnouncements;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Sender;
use App\Models\Courier;
use App\Models\MonthTranslation;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use App\Services\AnnouncementTranslationService;
use App\Services\AnnouncementMeasurementService;
use Illuminate\Support\Facades\App;

#[Title('Create sendannouncement')]
class CreateSender extends Component
{
    public string $type = 'sender';

    public $announcement = null;

    public $language;

    #[Validate('required|string|max:20')]
    public $itemName = '';

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
        'libraryValidated' => 'saveModelWithoutImages',
        'library-saved' => 'redirectAfterSave',
    ];

    public function mount(
        string $type = 'sender',
        $announcement = null
    ): void {
        $this->type = $type;

        $this->metricOrImperial = 'metric'; //metric | imperial |could store it in database

        if ($announcement) {
            $modelClass = $this->modelClass();

            $this->announcement = $modelClass::findOrFail($announcement);

            $this->authorize('update', $this->announcement);

            $this->language = Language::where('code', App::currentLocale())->first();

            $this->itemName = $this->announcement->translate($this->language->id)->thing;

            $this->description = $this->announcement->translate($this->language->id)->description;

            $this->dimensionLength = $this->announcement->getDimension($this->metricOrImperial)->length;

            $this->width = $this->announcement->getDimension($this->metricOrImperial)->width;

            $this->height = $this->announcement->getDimension($this->metricOrImperial)->height;

            $this->weight = $this->announcement->getWeight($this->metricOrImperial)->weight;

            $this->postingPlace = $this->announcement->translate($this->language->id)->posting_place;

            $this->receptionPlace = $this->announcement->translate($this->language->id)->reception_place;

            $this->postingDay = $this->announcement->posting_day;

            $this->postingMonth = $this->announcement->translate($this->language->id)->posting_month;

            $this->postingYear = $this->announcement->posting_year;

            $this->postingHour = $this->announcement->posting_hour;

            $this->postingMinute = $this->announcement->posting_minute;

            $this->receptionDay = $this->announcement->reception_day;

            $this->receptionMonth = $this->announcement->translate($this->language->id)->reception_month;

            $this->receptionYear = $this->announcement->reception_year;

            $this->receptionHour = $this->announcement->reception_hour;

            $this->receptionMinute = $this->announcement->reception_minute;
        }

        $this->metaDescription = 'Create sendannouncement';

        //day
        $this->currentDay = 1;

        //$this->calDaysInMonth = cal_days_in_month(CAL_GREGORIAN, date("n"), date("Y"));
        $this->calDaysInMonth = 31;

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

        $this->dimensionLength = $this->announcement->getDimension($this->metricOrImperial)->length;

        $this->width = $this->announcement->getDimension($this->metricOrImperial)->width;

        $this->height = $this->announcement->getDimension($this->metricOrImperial)->height;

        $this->weight = $this->announcement->getWeight($this->metricOrImperial)->weight;
    }

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

    public function save(
        AnnouncementTranslationService $translationService,
        AnnouncementMeasurementService $measurementService
    ) {
        $this->validate();

        if ($this->supportsImages()) {
            $this->dispatch('validateLibrary');

            return;
        }

        // courier
        $this->saveModelWithoutImages($translationService, $measurementService);
    }

    public function saveModelWithoutImages(
        AnnouncementTranslationService $translationService,
        AnnouncementMeasurementService $measurementService
    ) {
        if ($this->announcement) {
            $this->updateModel($translationService, $measurementService);
        } else {
            $this->createModel($translationService, $measurementService);
        }

        if ($this->supportsImages()) {
            $this->dispatch('updateLibraryModel', modelId: $this->announcement->id);

            return;
        }

        $this->redirectAfterSave();
    }

    public function createModel(
        AnnouncementTranslationService $translationService,
        AnnouncementMeasurementService $measurementService
    ) {
        $user = Auth::user();

        $modelClass = $this->modelClass();

        $this->announcement = $modelClass::create([
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

        $this->syncRelatedData(
            $translationService,
            $measurementService
        );
    }

    private function updateModel(
        AnnouncementTranslationService $translationService,
        AnnouncementMeasurementService $measurementService
    ): void {

        $this->announcement->update([
            'posting_day' => $this->postingDay,
            'posting_year' => $this->postingYear,
            'posting_hour' => $this->postingHour,
            'posting_minute' => $this->postingMinute,

            'reception_day' => $this->receptionDay,
            'reception_year' => $this->receptionYear,
            'reception_hour' => $this->receptionHour,
            'reception_minute' => $this->receptionMinute,
        ]);

        $this->syncRelatedData(
            $translationService,
            $measurementService
        );
    }

    private function syncRelatedData(
        AnnouncementTranslationService $translationService,
        AnnouncementMeasurementService $measurementService
    ): void {

        $translationService->syncTranslations(
            $this->announcement,
            [
                'thing' => $this->itemName,
                'description' => $this->description,
                'posting_place' => $this->postingPlace,
                'reception_place' => $this->receptionPlace,
                'posting_month' => $this->postingMonth,
                'reception_month' => $this->receptionMonth,
            ]
        );

        $measurementService->syncWeights(
            $this->announcement,
            $this->weight,
            $this->metricOrImperial
        );

        $measurementService->syncDimensions(
            $this->announcement,
            $this->dimensionLength,
            $this->width,
            $this->height,
            $this->metricOrImperial
        );
    }

    public function redirectAfterSave()
    {
        if ($this->supportsImages()) {
            $this->redirectRoute('senders-announcements.index');
        } else {
            $this->redirectRoute('couriers-announcements.index');
        }
    }

    public function onLibraryValidationFailed()
    {
        //$this->childValid = false;
        // Show error, halt further actions
        //session()->flash('error', 'Image validation failed. Please fix the errors.');
    }

    public function render()
    {
        return view('livewire.senders-announcements.create-sender');
    }
}
