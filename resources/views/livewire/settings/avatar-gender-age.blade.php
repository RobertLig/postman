<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads; // max:1024
    //200

    #[Validate('nullable|image|max:1024')]
    public $photo;

    #[Validate('nullable|string')]
    public $avatar;

    #[Validate('nullable|in:male,female')]
    public $gender;

    #[Validate('nullable|in:male,female')]
    public $genderPrev;

    #[Validate('nullable|string|in:< 20,20 to 29,30 to 39,40 to 49,50 to 59,60 to 69,70 to 79,80 to 89,> 90')]
    public $age;

    #[Validate('nullable|string|in:< 20,20 to 29,30 to 39,40 to 49,50 to 59,60 to 69,70 to 79,80 to 89,> 90')]
    public $agePrev;

    public function mount()
    {
        $user = Auth::user();

        if ($user->avatar) {
            $this->avatar = Storage::disk('public')->url($user->avatar);

            $this->photo = true; //to show trash bin
        }

        $this->gender = $user->gender;

        $this->genderPrev = $user->gender;

        $this->age = $user->age;

        $this->agePrev = $user->age;
    }

    protected function updatePhoto()
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
    }

    protected function updateGenderAndAge()
    {
        $user = Auth::user();

        $user->update(['gender' => $this->gender, 'age' => $this->age]);
    }

    public function resetGender()
    {
        if ($this->genderPrev === $this->gender) {
            $this->gender = null;

            $this->genderPrev = null;
        } else {
            $this->genderPrev = $this->gender;
        }
    }

    public function resetAge()
    {
        if ($this->agePrev === $this->age) {
            $this->age = null;

            $this->agePrev = null;
        } else {
            $this->agePrev = $this->age;
        }
    }

    public function updateProfile()
    {
        $this->updatePhoto();

        $this->updateGenderAndAge();
    }

    public function deletePhoto()
    {
        $user = Auth::user();

        if (!$user->avatar) {
            return;
        }

        Storage::disk('public')->delete($user->avatar);

        $user->update(['avatar' => null]);

        $this->dispatch('profile-updated');
    }
}; ?>

<div>
    <x-header subtitle="{{ __('This may be helpful for others to choose a specific courier.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Photo, gender and age') }}
        </x-slot>
    </x-header>

    <x-form wire:submit="updateProfile" no-separator>
        <div class="grid gap-15 sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-3xl">
            <!-- avatar -->
            <div>
                <x-file label="{{ __('Photo') }}" wire:model="photo" accept="image/*" {{-- "image/png, image/jpeg" --}}
                    change-text="{{ __('Change') }}">
                    <img src="{{ $avatar ?? Storage::disk('public')->url('avatars/empty-user.jpg') }}"
                        class="h-40 rounded-lg" />
                    {{-- $user->avatar --}}
                </x-file>
                @if ($photo)
                    {{--  --}}
                    <x-button
                        x-on:click="$wire.set('photo', null); $wire.deletePhoto(); document.querySelector('div[x-ref] img').src = '{{ Storage::disk('public')->url('avatars/empty-user.jpg') }}';"
                        icon="o-trash" class="btn-circle btn-ghost" tooltip-right="{{ __('Delete photo') }}" />
                    {{-- wire:click="resetAvatar" --}}
                @endif

                <x-hr target="deletePhoto" />
            </div>

            <!-- gender -->
            <x-gender />

            <!-- age -->
            <x-age />
        </div>

        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="updateProfile" />
        </x-slot:actions>
    </x-form>
</div>
