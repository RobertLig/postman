<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Mary\Traits\Toast;

new #[Title('Update password')] class extends Component {
    use Toast;

    #[Validate('required|current_password')]
    public string $current_password = '';

    #[Validate]
    public string $password = '';

    #[Validate('required')]
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'password' => ['required', Password::min(8)->letters()->numbers(), 'confirmed'],
        ];
    }

    public function updatePassword()
    {
        try {
            $validated = $this->validate();
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        Auth::logoutOtherDevices($validated['password']);

        $this->success(__('Password updated'), position: 'toast-bottom');
    }
}; ?>

<div>
    <x-header title="{{ __('Update password') }}"
        subtitle="{{ __('Ensure your account is using a long, random password to stay secure.') }}" separator />

    <x-form wire:submit="updatePassword">
        <x-password label="{{ __('Current password') }}" wire:model="current_password"
            placeholder="{{ __('Current password') }}" clearable />

        <x-password label="{{ __('New password') }}" wire:model="password" placeholder="{{ __('New password') }}"
            clearable />

        <x-password label="{{ __('Password confirmation') }}" wire:model="password_confirmation"
            placeholder="{{ __('Password confirmation') }}" clearable />

        <x-slot:actions>
            <x-button label="{{ __('Update password') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="updatePassword" wire:loading.attr="disabled" />
        </x-slot:actions>
    </x-form>
</div>
