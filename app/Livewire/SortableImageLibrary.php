<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class SortableImageLibrary extends Component
{
     use WithFileUploads;

    // Stored as a collection (array of ['url' => ...])
    #[Validate('array|max:4')]
    public $library;

    // For new uploads
    #[Validate(['files.*' => 'nullable|image|max:1024'])]
    public $files = [];

    public function mount($model = null)
    {
        // If editing, preload images from model
        $this->library = collect();
        if ($model && $model->library) {
            $this->library = collect(json_decode($model->library, true));
        }
    }

    public function updatedFiles()
    {
        foreach ($this->files as $file) {
            if ($this->library->count() >= 4) break;
            $path = $file->store('', 'senders-announcements');
            $this->library->push(['url' => Storage::disk('senders-announcements')->url($path)]);
        }
        $this->files = [];
    }

    public function removeImage($index)
    {
        $image = $this->library[$index] ?? null;
        if ($image && isset($image['url'])) {
            // Extract relative path from the URL
            $relativePath = str_replace('/storage/', '', parse_url($image['url'], PHP_URL_PATH));
            Storage::disk('senders-announcements')->delete($relativePath);
        }
        $this->library = $this->library->forget($index)->values();
    }

    public function moveImage($from, $to)
    {
        $images = $this->library->all();
        $moved = array_splice($images, $from, 1);
        array_splice($images, $to, 0, $moved);
        $this->library = collect($images);
    }

    public function save()
    {
        $this->validate();

        // Save as JSON, nullable
        $this->model->library = $this->library->isEmpty() ? null : $this->library->toJson();
        $this->model->save();

        //session()->flash('success', 'Images saved!');
    }

    public function render()
    {
        return view('livewire.sortable-image-library');
    }
}
