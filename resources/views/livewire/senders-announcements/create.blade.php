<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use App\Models\SenderAnnouncement;
use App\Models\MonthTranslation;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use App\Livewire\SortableImageLibrary;
use Livewire\Attributes\On;
use App\Services\AnnouncementTranslationService;
use App\Services\AnnouncementMeasurementService;

new #[Title('Create senders` announcement')] class extends Component {
    use WithFileUploads; //, WithMediaSync

    // Stored as a collection (array of ['url' => ...])
    #[Validate('array|max:4')]
    public $library; // Existing images (from DB)
    //max:1024

    // For new uploads
    #[Validate(['files.*' => 'nullable|image|max:200'])]
    public array $files = []; // Newly uploaded images

    public $allImages = []; // Combined and sorted images

    public $model;

    //#[Validate(['files.*' => 'nullable|image|max:1024'])]
    //public array $files = [];

    //public Collection $library; //#[Validate('required')] //Mary image sortable solution

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

    public $senderAnnouncement;

    //public $childValid = false;

    /* protected $listeners = [
        'libraryValidated' => 'onLibraryValidated',
        'library-saved' => 'createDependencies',
    ];*/ //'librarySaved' => 'onLibrarySaved',
    //'libraryValidationFailed' => 'onLibraryValidationFailed',

    //public $uniqueKey;

    public string $metaDescription;

    public function mount(): void
    {
        //$this->uniqueKey = (string) \Illuminate\Support\Str::uuid();

        $this->metaDescription = 'Create senders` announcement';

        $this->model = null;
        if ($this->model && $this->model->library) {
            $this->library = $this->model->library;
        } else {
            $this->library = collect();
        }
        $this->mergeImages();

        // Load existing library metadata from your model
        //$this->library = $this->user->library;

        // Or ... an empty collection if this component creates a user
        //$this->library = new Collection();

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

    //images logic
    public function updatedFiles()
    {
        //$this->validate();
        $max = 4;
        $existing = $this->library->count();
        $new = count($this->files);

        if ($existing + $new > $max) {
            // Only allow up to (max - existing) new files
            $allowed = $max - $existing;
            $this->files = array_slice($this->files, 0, $allowed);
        }
        $this->mergeImages();
    }

    public function removeImage($index)
    {
        $image = $this->allImages[$index] ?? null;

        if (!$image) {
            return;
        }

        // Remove from files (new uploads)
        if (isset($image['is_new']) && $image['is_new']) {
            foreach ($this->files as $i => $file) {
                if ($file->getFilename() == $image['filename']) {
                    unset($this->files[$i]);
                    $this->files = array_values($this->files);
                    break;
                }
            }
        } else {
            // Remove from library (existing)
            foreach ($this->library as $i => $img) {
                if ($img['path'] == $image['path']) {
                    Storage::disk('senders-announcements')->delete($img['path']);
                    $this->library = $this->library->forget($i)->values();
                    break;
                }
            }
        }

        $this->mergeImages();
    }

    public function moveImage($params = null)
    {
        if (!is_array($params)) {
            return;
        }
        $from = $params['oldIndex'];
        $to = $params['newIndex'];

        $images = $this->allImages;
        $moved = array_splice($images, $from, 1);
        array_splice($images, $to, 0, $moved);
        $this->allImages = array_values($images);

        // Sync new order to library/files
        $this->syncOrder();
    }

    private function mergeImages()
    {
        $images = [];

        // Existing images
        foreach ($this->library as $img) {
            $images[] = [
                'url' => $img['url'],
                'path' => $img['path'],
                'is_new' => false,
            ];
        }

        // New images
        foreach ($this->files as $file) {
            $images[] = [
                'url' => $file->temporaryUrl(),
                'filename' => $file->getFilename(),
                'is_new' => true,
            ];
        }

        $this->allImages = $images;
    }

    private function syncOrder()
    {
        $newLibrary = collect();
        $newFiles = [];

        foreach ($this->allImages as $img) {
            if (isset($img['is_new']) && $img['is_new']) {
                // Find the file by filename
                foreach ($this->files as $file) {
                    if ($file->getFilename() == $img['filename']) {
                        $newFiles[] = $file;
                        break;
                    }
                }
            } else {
                $newLibrary->push(['url' => $img['url'], 'path' => $img['path']]);
            }
        }

        $this->library = $newLibrary;
        $this->files = $newFiles;
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

        //dd('last leg');

        $user = Auth::user();

        $this->senderAnnouncement = SenderAnnouncement::create([
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

        $finalImages = [];
        foreach ($this->allImages as $img) {
            if (isset($img['is_new']) && $img['is_new']) {
                // Store new file
                foreach ($this->files as $i => $file) {
                    if ($file->getFilename() == $img['filename']) {
                        $path = $file->store('', 'senders-announcements');
                        $finalImages[] = [
                            'url' => Storage::disk('senders-announcements')->url($path),
                            'path' => $path,
                        ];
                        unset($this->files[$i]);
                        break;
                    }
                }
            } else {
                // Already stored
                $finalImages[] = [
                    'url' => $img['url'],
                    'path' => $img['path'],
                ];
            }
        }

        $this->model = $this->senderAnnouncement;

        // Save to DB if model available
        if ($this->model) {
            $this->model->library = empty($finalImages) ? null : $finalImages;
            $this->model->save();
        }

        $this->library = collect($finalImages);
        $this->files = [];
        $this->mergeImages(); //(?) finished images logic

        app(AnnouncementTranslationService::class)->createSenderTranslations($this->senderAnnouncement, [
            'thing' => $this->thing,
            'description' => $this->description,
            'posting_place' => $this->postingPlace,
            'reception_place' => $this->receptionPlace,
            'posting_month' => $this->postingMonth,
            'reception_month' => $this->receptionMonth,
        ]);

        app(AnnouncementMeasurementService::class)->createSenderWeights($this->senderAnnouncement, $this->weight, $this->metricOrImperial);

        app(AnnouncementMeasurementService::class)->createSenderDimensions($this->senderAnnouncement, $this->dimensionLength, $this->width, $this->height, $this->metricOrImperial);

        $this->redirectRoute('senders-announcements.index');
    }

    public function onLibraryValidationFailed()
    {
        $this->childValid = false;
        // Show error, halt further actions
        //session()->flash('error', 'Image validation failed. Please fix the errors.');
    }
}; ?>

<div>
    <x-header title="{{ __('Create senders` announcement') }}"
        subtitle="{{ __('If you would like to send something, please fill out the form and post an ad.') }}" separator />

    <x-form wire:submit="save">
        <x-input label="{{ __('A thing') }}" wire:model.live="thing" placeholder="{{ __('A thing') }}"
            icon="o-question-mark-circle" clearable />

        <x-hr target="thing" />

        <div>
            <ul id="image-list" x-data x-init="Sortable.create($el, {
                animation: 150,
                onEnd: function(evt) {
                    $wire.moveImage({ oldIndex: evt.oldIndex, newIndex: evt.newIndex });
                }
            })">
                @foreach ($allImages as $i => $img)
                    <li class="flex items-center gap-2 bg-base-100 rounded-lg p-2" data-id="{{ $i }}">
                        <img src="{{ $img['url'] }}" class="w-24 h-24 object-cover rounded-lg" />
                        <button type="button" wire:click="removeImage({{ $i }})"
                            class="btn btn-error btn-sm ml-2">{{ __('Delete') }}</button>
                    </li>
                @endforeach
            </ul>

            @if (count($allImages) < 4)
                <div>
                    <label class="btn cursor-pointer">
                        {{ __('Add Images') }}
                        <input type="file" multiple wire:model="files" accept="image/*" class="hidden" />
                    </label>
                    <p class="mt-2 text-xs" style="color: var(--p);">
                        {{ __('Tip: To add multiple images, select them all at once in the file picker.') }}
                    </p>
                </div>
            @endif
            @error('files.*')
                <span class="text-error">{{ $message }}</span>
            @enderror
            <x-hr target="files" />
        </div>

        {{-- @livewire('sortable-image-library', ['model' => $senderAnnouncement], key($senderAnnouncement->id ?? $uniqueKey)) --}}
        {{-- <livewire:sortable-image-library :model="$senderAnnouncement" wire:key="sortable-images-{{ $senderAnnouncement->id ?? $uniqueKey }}" /> --}}

        {{-- <x-image-library
            wire:model="files"                 
            wire:library="library"             
            :preview="$library"                
            label="{{ __('Photos of the item') }}"
            hint="{{ __('Max 4 photos') }}" 
            add-files-text="{{ __('Add images') }}" 
            crop-title-text="{{ __('Crop image') }}" 
            crop-cancel-text="{{ __('Cancel') }}"
            crop-save-text="{{ __('Crop') }}"
            crop-text="{{ __('Crop') }}"
            remove-text="{{ __('Remove') }}" 
            change-text="{{ __('Change') }}" /> --}}

        <x-textarea label="{{ __('Item description') }}" wire:model.live="description"
            placeholder="{{ __('Item description') }}" hint="{{ __('Max 200 chars') }}" rows="5" />

        <x-hr target="description" />

        <x-dimensions-weight label="{{ __('Dimensions and weight') }}"
            class="sm:grid-cols-3 sm:gap-x-5 md:grid-cols-4" />

        <x-place-autocomplete />

        {{-- <x-map /> --}}

        <x-create-resource-section label="{{ __('Posting date and hour') }}"
            class="sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 max-w-3xl"> {{-- sm:grid-cols-2 xl:grid-cols-3 max-w-3xl --}}

            {{-- <livewire:announcement.post-day /> component not working. Couldn't reset properties on Alpine with $wire.entangle() during livewire server roundtrip. Issue not solved --}}

            <x-carousela class="" :data-carousel="$dataDay" input="{{ $currentDay }}"
                total-value="{{ $calDaysInMonth }}" start-value="1" model-name="postingDay" is-live="true"
                prefix-zero="false" :text-values="$textValuesDay">

                <x-slot:input-element>
                    <x-input label="{{ __('Day') }}" wire:model.live="postingDay"
                        placeholder="{{ __('Day') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingDay" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="w-25" :data-carousel="$dataMonth" input="{{ $currentMonth }}" total-value="11" start-value="0"
                model-name="postingMonth" is-live="true" prefix-zero="false" :text-values="$textValuesMonth">

                <x-slot:input-element>
                    <x-input label="{{ __('Month') }}" wire:model.live="postingMonth"
                        placeholder="{{ __('Month') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingMonth" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataYear" input="{{ $currentYear }}"
                total-value="{{ $currentYear + 17 }}" start-value="{{ $currentYear - 1 }}" model-name="postingYear"
                is-live="true" prefix-zero="false" :text-values="$textValuesYear">

                <x-slot:input-element>
                    <x-input label="{{ __('Year') }}" wire:model.live="postingYear"
                        placeholder="{{ __('Year') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingYear" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataHour" input="{{ $currentHour }}" total-value="23" start-value="0"
                model-name="postingHour" is-live="true" prefix-zero="false" :text-values="$textValuesHour">

                <x-slot:input-element>
                    <x-input label="{{ __('Hour') }}" wire:model.live="postingHour"
                        placeholder="{{ __('Hour') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingHour" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataMinute" input="{{ $currentMinute }}" total-value="59" start-value="0"
                model-name="postingMinute" is-live="true" prefix-zero="true" :text-values="$textValuesMinute">

                <x-slot:input-element>
                    <x-input label="{{ __('Minute') }}" wire:model.live="postingMinute"
                        placeholder="{{ __('Minute') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingMinute" />
                </x-slot:progress>
            </x-carousela>

        </x-create-resource-section>

        <x-create-resource-section label="{{ __('Reception date and hour') }}"
            class="sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 max-w-3xl">

            <x-carousela class="" :data-carousel="$dataDay" input="{{ $currentDay }}"
                total-value="{{ $calDaysInMonth }}" start-value="1" model-name="receptionDay" is-live="true"
                prefix-zero="false" :text-values="$textValuesDay">

                <x-slot:input-element>
                    <x-input label="{{ __('Day') }}" wire:model.live="receptionDay"
                        placeholder="{{ __('Day') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionDay" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="w-25" :data-carousel="$dataMonth" input="{{ $currentMonth }}" total-value="11"
                start-value="0" model-name="receptionMonth" is-live="true" prefix-zero="false" :text-values="$textValuesMonth">

                <x-slot:input-element>
                    <x-input label="{{ __('Month') }}" wire:model.live="receptionMonth"
                        placeholder="{{ __('Month') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionMonth" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataYear" input="{{ $currentYear }}"
                total-value="{{ $currentYear + 17 }}" start-value="{{ $currentYear - 1 }}"
                model-name="receptionYear" is-live="true" prefix-zero="false" :text-values="$textValuesYear">

                <x-slot:input-element>
                    <x-input label="{{ __('Year') }}" wire:model.live="receptionYear"
                        placeholder="{{ __('Year') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionYear" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataHour" input="{{ $currentHour }}" total-value="23"
                start-value="0" model-name="receptionHour" is-live="true" prefix-zero="false" :text-values="$textValuesHour">

                <x-slot:input-element>
                    <x-input label="{{ __('Hour') }}" wire:model.live="receptionHour"
                        placeholder="{{ __('Hour') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionHour" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataMinute" input="{{ $currentMinute }}" total-value="59"
                start-value="0" model-name="receptionMinute" is-live="true" prefix-zero="true" :text-values="$textValuesMinute">

                <x-slot:input-element>
                    <x-input label="{{ __('Minute') }}" wire:model.live="receptionMinute"
                        placeholder="{{ __('Minute') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionMinute" />
                </x-slot:progress>
            </x-carousela>

        </x-create-resource-section>

        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
