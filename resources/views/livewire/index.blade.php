<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Home')] class extends Component {
    //
}; ?>

<div>

    @if (session()->has('success'))
        <x-alert icon="o-check-circle" class="alert-success mb-5">

            {{ session('success') }}

        </x-alert>
    @endif

    <x-hero class="rounded-ee-full bg-base-200">

        <x-slot:title class="leading-20 text-6xl">
            {{ __('Ship faster') }}
        </x-slot>

        <x-slot:subtitle>
            {{ __('Without post.') }}
        </x-slot>

        <div class="mt-4 flex justify-end">
            <x-button label="{{ __('Courier`s announcements') }}" icon="o-clipboard-document-list"
                link="{{ route('couriers') }}" class="btn btn-primary" :badge="\App\Models\Courier::all()->count()" />
        </div>

    </x-hero>

    <x-hero class=" mt-30 justify-center">
        <x-slot:title class="leading-11 text-3xl text-center">
            {{ __('Can\'t find a carrier?') }}
        </x-slot>

        <x-slot:subtitle class="text-center">
            {{ __('Place an ad that you want to send something.') }}
        </x-slot>

        <x-actions-advertisers-reviews class="justify-center">
            <x-button label="{{ __('Add an ad') }}" icon="o-clipboard-document-list"
                link="{{ route('senders.create') }}" class="btn btn-primary" />
        </x-actions-advertisers-reviews>
    </x-hero>

    <x-shapes.arc-top />

    <x-hero class="bg-base-200 justify-end pt-0">
        <x-slot:title class="leading-11 text-3xl text-end">
            {{ __('Or maybe you are going somewhere') }}
        </x-slot>

        <x-slot:subtitle class="text-end">
            {{ __('And you\'d like to drop something off for someone.') }}
        </x-slot>

        <div class="mt-4 flex justify-end">
            <x-button label="{{ __('Senders` announcements') }}" icon="o-clipboard-document-list"
                link="{{ route('senders') }}" class="btn btn-primary" :badge="\App\Models\Sender::all()->count()" />
        </div>

    </x-hero>

    <x-hero class="bg-base-200 mt-30 justify-end rounded-es-full">
        <x-slot:title class="leading-11 text-3xl text-end">
            {{ __('If no one is shipping where you are going') }}
        </x-slot>

        <x-slot:subtitle class="text-end">
            {{ __('Advertise yourself that you are going.') }}
        </x-slot>

        <x-actions-advertisers-reviews class="justify-end">
            <x-button label="{{ __('Add an ad') }}" icon="o-clipboard-document-list"
                link="{{ route('couriers.create') }}" class="btn btn-primary" />
        </x-actions-advertisers-reviews>
    </x-hero>

    <div class="divider"></div>

    <x-reviews />

    <div class="divider"></div>
</div>
