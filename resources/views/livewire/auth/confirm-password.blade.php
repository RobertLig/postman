<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

new #[Title('Confirm password')] class extends Component {
    #[Validate]
    public $password = '';

    protected function rules()
    {
        return [
            'password' => ['required', Password::min(8)->letters()->numbers()],
        ];
    }

    public function confirmPassword()
    {
        $this->validate();

        if (
            !Auth::guard('web')->validate([
                'email' => Auth::user()->email,
                'password' => $this->password,
            ])
        ) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(route('home'));
    }
}; ?>

<div>
    <x-header title="{{ __('Confirm password') }}"
        subtitle="{{ __('This is a secure area of the application. Please confirm your password before proceeding.') }}"
        separator />

    <x-form wire:submit="confirmPassword">
        <x-password label="{{ __('Password') }}" wire:model="password" placeholder="{{ __('Password') }}" clearable />

        <x-slot:actions>
            <x-button label="{{ __('Confirm') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="confirmPassword" wire:loading.attr="disabled" />
        </x-slot:actions>
    </x-form>
</div>
