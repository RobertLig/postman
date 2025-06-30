<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <x-hero class="rounded-ee-full bg-base-200 justify-end">
        <h1 class="font-bold leading-20 text-6xl">{{ __('Ship faster') }}</h1>

        <x-slot:subtitle>
            {{ __('Without post.') }}
        </x-slot>

        <x-slot:actions>
            <x-button label="{{ __('Courier`s announcements') }}" icon="o-clipboard-document-list" link="" class="btn btn-primary" />
        </x-slot>

        <x-slot:advertisers>
            <x-advertisers />
        </x-slot>

        <x-slot:reviews>
            <x-google-reviews />
        </x-slot>
    </x-hero>

    <x-hero class="bg-base-200 mt-30">
        <h1 class="font-bold leading-11 text-3xl text-center">{{ __('Can\'t find a carrier?') }}</h1>

        <x-slot:subtitle class="text-center">
            {{ __('Place an ad that you want to send something.') }}
        </x-slot>

        <x-slot:actions>
            <x-button label="{{ __('Add an ad') }}" icon="o-clipboard-document-list" link="" class="btn btn-primary mx-auto" />
        </x-slot>
    </x-hero>

    <x-hero class="rounded-ss-full rounded-se-full bg-base-200 sm:mt-30 justify-end">
        <x-slot:title class="leading-11 text-3xl text-end">
            {{ __('Or maybe you are going somewhere') }}
        </x-slot>
        
        <x-slot:subtitle class="text-end">
            {{ __('And you\'d like to drop something off for someone.') }}
        </x-slot>

        <x-actions-advertisers-reviews class="justify-end">
            <x-button label="{{ __('Senders` announcements') }}" icon="o-clipboard-document-list" link="" class="btn btn-primary" />

            <x-slot:advertisersreviews>               
                <x-advertisers />
                <x-google-reviews />
            </x-slot>
        </x-actions-advertisers-reviews>
    </x-hero> 
</div>
