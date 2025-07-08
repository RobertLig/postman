<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public function sendVerification()
    {
        Auth::user()->sendEmailVerificationNotification();
    }
}; ?>

<div>
    <x-verify-email-notice class="sm:h-screen w-full justify-center items-center">
        <x-slot:title class="leading-9 text-2xl text-center">
            {{ __('Verify your email') }}
        </x-slot>

        <x-slot:subtitle class="leading-7 text-center">
            {{ __('An email has been sent to you with a link to verify your account.') }}
        </x-slot>

        <x-slot:section class="leading-7 text-center">
            {{ __('If you did not receive the email or the link is broken, click the button below to obtain a new email with the link.') }}
        </x-slot>

        <x-slot:actions class="text-center">
            <x-button label="{{ __('Send email') }}" icon="o-paper-airplane" link="" class="btn btn-primary" wire:click="sendVerification" />
        </x-slot>
    </x-verify-email-notice>
</div>
