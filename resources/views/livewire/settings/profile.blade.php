<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mary\Traits\Toast;

new #[Title('Update profile')] class extends Component {
    use Toast;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email')]
    public $email = ''; //|unique:users

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfile()
    {
        $validated = $this->validate();

        // Get the current user
        $user = Auth::user();

        $user->update($validated);

        // Regenerate the session
        Session::regenerate(); //this causes page expired error (418)

        $this->dispatch('profile-updated');

        $this->success(__('Profile updated'), position: 'toast-bottom');
    }
}; ?>

<div>
    <x-header title="{{ __('Update profile') }}" subtitle="{{ __('You can update your name and email address here.') }}"
        separator />

    <x-form wire:submit="updateProfile" no-separator>
        <x-input label="{{ __('Name') }}" wire:model="name" placeholder="{{ __('Your name') }}" icon="o-user"
            hint="{{ __('Your full name') }}" clearable />

        <x-input label="{{ __('E-Mail Address') }}" wire:model="email" placeholder="{{ __('mail@site.com') }}"
            icon="o-envelope" clearable />

        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="updateProfile" wire:loading.attr="disabled" />
        </x-slot:actions>
    </x-form>

    <div class="divider"></div>

    <div class="mt-20 grid max-w-4xl gap-x-12 gap-y-16 sm:grid-cols-2 xl:grid-cols-3">

        <livewire:settings.avatar />

        <livewire:settings.gender />

        <livewire:settings.age />

    </div>

    <div class="divider"></div>

    <livewire:settings.my-announcements />

    {{-- <div class="divider"></div> --}}

    <livewire:settings.delete-account />
</div>
