<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

new #[Title('Update password')]
class extends Component {
    #[Validate('required|current_password')]
    public $current_password = '';

    #[Validate]
    public $password = '';

    #[Validate('required|same:password')]
    public $password_confirmation = '';

    protected function rules() 
    {
        return [
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ];
    }

    public function updatePassword()
    {
        $this->validate();


    }
}; ?>

<div>
    <x-header title="{{ __('Update password') }}" subtitle="{{ __('Ensure your account is using a long, random password to stay secure.') }}" separator />

    <x-form wire:submit="updatePassword">
        <x-password label="{{ __('Current password') }}" wire:model="current_password" placeholder="{{ __('Current password') }}"  clearable />

        <x-password label="{{ __('New password') }}" wire:model="password" placeholder="{{ __('New password') }}"  clearable />

        <x-password label="{{ __('Password confirmation') }}" wire:model="password_confirmation" placeholder="{{ __('Password confirmation') }}" clearable />
  
        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="updatePassword" />
        </x-slot:actions>
    </x-form>
</div>
