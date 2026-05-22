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

    #[Validate('nullable|image|max:1024')]
    public $photo;

    #[Validate('nullable|string')]
    public $avatar;

    public function mount(): void
    {
        $user = Auth::user();

        if ($user->avatar) {
            $this->avatar = Storage::disk('public')->url($user->avatar);

            $this->photo = true; //to show trash bin
        }
    }

    public function updatePhoto(): void
    {
        $this->validate(); //not needed for file?

        if ($this->photo == null || $this->photo === true) {
            return;
        }

        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $this->photo->store('avatars', 'public');

        //update database
        $user->update(['avatar' => $path]);

        $this->dispatch('profile-updated');

        $this->success(__('Your photo has been updated successfully!'), position: 'toast-bottom');
    }

    public function deletePhoto(): void
    {
        $user = Auth::user();

        if (!$user->avatar) {
            return;
        }

        Storage::disk('public')->delete($user->avatar);

        $user->update(['avatar' => null]);

        $this->dispatch('profile-updated');

        $this->success(__('Your photo has been deleted successfully!'), position: 'toast-bottom');
    }

    public function render()
    {
        return view('livewire.settings.avatar');
    }
}
