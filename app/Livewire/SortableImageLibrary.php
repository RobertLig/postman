<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use App\Models\SenderAnnouncement;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Log;

class SortableImageLibrary extends Component
{
    use WithFileUploads;

    // Stored as a collection (array of ['url' => ...])
    #[Validate('array|max:4')]
    public $library;

    // For new uploads
    #[Validate(['files.*' => 'nullable|image|max:1024'])]
    public $files = [];

    public $model;

    protected $listeners = ['validateLibrary' => 'onValidateLibrary',
                            'updateLibraryModel' => 'setModel',
                            'saveLibrary' => 'save'];

    public function mount($model = null)
    {
        $this->model = $model;
        // If editing, preload images from model
        if ($this->model && $this->model->library) {
            $this->library = collect(json_decode($this->model->library, true));
        } else {
            $this->library = collect();
        }
    }

    public function updatedFiles()
    {
        foreach ($this->files as $file) {
            if ($this->library->count() >= 4) break;
            $path = $file->store('', 'senders-announcements');
            $this->library->push([
                'url' => Storage::disk('senders-announcements')->url($path),
                'path' => $path, // Save the storage path
            ]);
        }
        $this->files = [];
    }

    public function removeImage($index)
    {
        $image = $this->library[$index] ?? null;
        if ($image && isset($image['path'])) {
            Storage::disk('senders-announcements')->delete($image['path']);
        }
        $this->library = $this->library->forget($index)->values();
    }

    //#[On('moveImageSortable')]
    public function moveImage($params = null)
    {
        //Log::info('moveImage params', ['params' => $params, 'library_type' => gettype($this->library)]);
    
        if (!is_array($params)) {
            //Log::error('moveImage called without valid params', ['params' => $params]);
            return;
        }

        if (!is_array($params)) return;

        //correct
        $from = $params['oldIndex'];
        $to = $params['newIndex'];
        $images = $this->library->all();
        $moved = array_splice($images, $from, 1);
        array_splice($images, $to, 0, $moved);
        $this->library = collect(array_values($images));
        $this->dispatch('$refresh');
    }

    public function onValidateLibrary()
    {
        try {
            $this->validate(); // Validate child (images)
            $this->dispatch('libraryValidated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            //$this->dispatch('libraryValidationFailed');
            // Optionally rethrow for Livewire to show errors on child
            throw $e;
        }
    }

    public function setModel($modelId)
    {
        $this->model = SenderAnnouncement::find($modelId); 

        $this->save();
    }

    public function save()
    {
        // Save as JSON, nullable
        $this->model->library = $this->library->isEmpty() ? null : $this->library->toJson();
        $this->model->save();

        //session()->flash('success', 'Images saved!');

        //$this->dispatch('librarySaved');
    }

    public function render()
    {
        return view('livewire.sortable-image-library');
    }
}
