<?php

use Livewire\Volt\Component;

new class extends Component {
    //public $photo;
}; ?>

<div>
    <x-header subtitle="{{ __('This may be helpful for others to choose a specific courier.') }}" separator>
        <x-slot:title class="!text-xl">
            {{ __('Photo, gender and age') }}
        </x-slot>
    </x-header>

    <x-form wire:submit="updateProfile" no-separator>
        <div class="grid gap-15 sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-2xl">
            <x-file wire:model="photo" accept="image/png, image/jpeg"> 
                <img src="{{ $user->avatar ?? Storage::url('avatars/empty-user.jpg') }}" class="h-40 rounded-lg" />
            </x-file> 
        </div>
  
        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="updateProfile" />
        </x-slot:actions>
    </x-form>
</div>
