<?php

namespace App\Livewire\Settings;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;

use Livewire\Component;

class Avatar extends Component
{
    use WithFileUploads, Toast;

    #[Validate('nullable|image|mimes:jpg,jpeg,png,webp|max:1024')]
    public $photo;

    #[Validate('nullable|string')]
    public ?string $avatar = null;

    public int $iteration = 0;

    public function mount(): void
    {
        $user = Auth::user();

        if ($user->avatar) {
            $this->avatar = $this->storage()->url($user->avatar);
        }
    }

    public function updatePhoto(): void
    {
        $this->validate();

        if (!$this->photo) {
            return;
        }

        $user = Auth::user();

        if ($user->avatar) {
            $this->storage()->delete($user->avatar);
        }

        $path = $this->photo->store('avatars', 'public');

        $user->update([
            'avatar' => $path,
        ]);

        $this->avatar = $this->storage()->url($path);

        $this->photo = null;

        $this->dispatch('profile-updated');

        $this->success(
            __('Your photo has been updated successfully!'),
            position: 'toast-bottom'
        );
    }

    public function deletePhoto(): void
    {
        $user = Auth::user();

        if (!$user->avatar) {
            return;
        }

        $this->storage()->delete($user->avatar);

        $user->update([
            'avatar' => null,
        ]);

        $this->avatar = null;
        $this->photo = null;

        $this->iteration++;

        $this->dispatch('profile-updated');

        $this->success(
            __('Your photo has been deleted successfully!'),
            position: 'toast-bottom'
        );
    }

    protected function storage()
    {
        return Storage::disk('public');
    }

    public function render()
    {
        return view('livewire.settings.avatar');
    }
}
