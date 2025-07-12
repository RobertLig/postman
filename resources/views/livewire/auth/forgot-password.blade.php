<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Mary\Traits\Toast;
use Livewire\Attributes\Title;

new #[Title('Forgot password')]
class extends Component {
    use Toast;

    #[Validate('required|email')]
    public $email = '';

    public function sendPasswordResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink($this->only('email'));

        $status === Password::ResetLinkSent
            ? $this->success(
                __($status), 
                position: 'toast-bottom'
            )

            : $this->error(
                __($status),
                position: 'toast-bottom',
                timeout: 5000,
            );   
    }
}; ?>

<div>
    <x-header title="{{ __('Forgot password') }}" subtitle="{{ __('Enter your email to receive a password reset link.') }}" separator />

    <x-form wire:submit="sendPasswordResetLink">
        <x-input label="{{ __('E-Mail Address') }}" wire:model="email" placeholder="{{ __('mail@site.com') }}" icon="o-envelope"  clearable /> 

        <x-slot:actions>
            <x-button label="{{ __('Email password reset') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="sendPasswordResetLink" />
        </x-slot:actions>
    </x-form>

    <div class="text-end pt-4 text-sm">
        {{ __('Or, return to') }} 
        <a href="{{ route('login') }}" class="link text-sm">{{ __('log in') }} </a>
    </div>
</div>
