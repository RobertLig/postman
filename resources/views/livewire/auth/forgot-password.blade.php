<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Mary\Traits\Toast;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\RateLimiter;

new #[Title('Forgot password')] class extends Component {
    use Toast;

    #[Validate('required|email')]
    public $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->email = strtolower(trim($this->email));

        $this->validate();

        $key = 'password-reset:' . sha1($this->email);

        $executed = RateLimiter::attempt($key, 5, function () {
            $status = Password::sendResetLink($this->only('email'));

            $status === Password::ResetLinkSent ? $this->success(__($status), position: 'toast-bottom') : $this->error(__($status), position: 'toast-bottom', timeout: 5000);
        });

        if (!$executed) {
            $this->error(__('email.throttle', ['seconds' => RateLimiter::availableIn($key)]), position: 'toast-bottom', timeout: 5000);
        }
    }
}; ?>

<div>
    <x-header title="{{ __('Forgot password') }}"
        subtitle="{{ __('Enter your email to receive a password reset link.') }}" separator />

    <x-form wire:submit="sendPasswordResetLink">
        <x-input label="{{ __('E-Mail Address') }}" wire:model="email" placeholder="{{ __('mail@site.com') }}"
            icon="o-envelope" clearable />

        <x-slot:actions>
            <x-button label="{{ __('Send reset link') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="sendPasswordResetLink" wire:loading.attr="disabled" />
        </x-slot:actions>
    </x-form>

    <div class="text-end pt-4 text-sm">
        {{ __('Or, return to') }}
        <a href="{{ route('login') }}" class="link text-sm">{{ __('log in') }} </a>
    </div>
</div>
