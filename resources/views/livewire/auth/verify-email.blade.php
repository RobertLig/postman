<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\RateLimiter;

new class extends Component {
    use Toast;

    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return;
        }

        $key = 'sendMail:' . Auth::id();

        $executed = RateLimiter::attempt($key, 1, function () {
            Auth::user()->sendEmailVerificationNotification();

            $this->success(__('email.sent'), position: 'toast-bottom');
        });

        if (!$executed) {
            $this->error(__('email.throttle', ['seconds' => RateLimiter::availableIn($key)]), position: 'toast-bottom', timeout: 5000);
        }
    }
}; ?>

<div class="min-h-screen flex items-center justify-center px-4">

    <x-verify-email-notice class="w-full max-w-2xl">

        <x-slot:title class="leading-9 text-2xl text-center">
            {{ __('Verify your email') }}
        </x-slot>

        <x-slot:subtitle class="leading-7 text-center">
            {{ __('We have sent you an email containing a verification link.') }}
        </x-slot>

        <x-slot:section class="leading-7 text-center">
            {{ __('If you did not receive the email or the link has expired, click the button below to obtain a new email with the link.') }}
        </x-slot>

        <x-slot:actions class="text-center">
            <x-button label="{{ __('Send email') }}" icon="o-paper-airplane" class="btn-primary"
                wire:click="sendVerification" spinner="sendVerification" wire:loading.attr="disabled" />
        </x-slot>

    </x-verify-email-notice>

</div>
