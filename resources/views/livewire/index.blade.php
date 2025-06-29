<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <x-hero class="rounded-ee-full">
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
</div>
