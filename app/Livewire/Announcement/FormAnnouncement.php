<?php

namespace App\Livewire\Announcement;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use App\Models\Sender;
use App\Models\Courier;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use App\Services\AnnouncementTranslationService;
use App\Services\AnnouncementMeasurementService;
use Illuminate\Support\Facades\App;
use Mary\Traits\Toast;

#[Title('Create sendannouncement')]
class FormAnnouncement extends Component
{
    use Toast;

    public string $type = 'sender';

    public $announcement = null;

    public $language;

    #[Validate('required|string|max:100')]
    public $itemName = '';

    #[Validate('nullable|string|max:1000')]
    public $description;

    #[Validate('required|string|in:metric,imperial')]
    public $metricOrImperial;

    #[Validate('nullable|integer|min:1')]
    public ?int $dimensionLength = null;

    #[Validate('nullable|integer|min:1')]
    public ?int $width = null;

    #[Validate('nullable|integer|min:1')]
    public ?int $height = null;

    #[Validate('nullable|integer|min:1')]
    public ?int $weight = null;

    #[Validate('required|date')]
    public $posting_at;

    #[Validate('required|date')]
    public $reception_at;

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

            $this->posting_at = $this->announcement->posting_at?->format('Y-m-d\TH:i');

            $this->reception_at = $this->announcement->reception_at?->format('Y-m-d\TH:i');
        }

        $this->metaDescription = 'Create sendannouncement';
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

        if ($this->announcement) {
            $this->dimensionLength = $this->announcement->getDimension($this->metricOrImperial)->length;

            $this->width = $this->announcement->getDimension($this->metricOrImperial)->width;

            $this->height = $this->announcement->getDimension($this->metricOrImperial)->height;

            $this->weight = $this->announcement->getWeight($this->metricOrImperial)->weight;
        }
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

            'posting_at' => $this->posting_at,
            'reception_at' => $this->reception_at,
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
            'posting_at' => $this->posting_at,
            'reception_at' => $this->reception_at,
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
                'reception_place' => $this->receptionPlace
            ]
        );

        $measurementService->syncWeights(
            $this->announcement,
            $this->weight ? (int) $this->weight : null,
            $this->metricOrImperial
        );

        $measurementService->syncDimensions(
            $this->announcement,
            $this->dimensionLength ? (int) $this->dimensionLength : null,
            $this->width ? (int) $this->width : null,
            $this->height ? (int) $this->height : null,
            $this->metricOrImperial
        );
    }

    public function redirectAfterSave()
    {
        if ($this->supportsImages()) {
            $this->success(__('Your ad has been posted successfully!'), position: 'toast-bottom', redirectTo: route('senders'));
        } else {
            $this->success(__('Your ad has been posted successfully!'), position: 'toast-bottom', redirectTo: route('couriers'));
        }
    }

    public function onLibraryValidationFailed()
    {
        //
    }

    public function render()
    {
        return view('livewire.announcement.form-announcement');
    }
}
