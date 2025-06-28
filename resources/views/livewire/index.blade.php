<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <x-hero>
        <span class="text-6xl">{{ __('Ship faster') }}</span>

        <x-slot:subtitle>
            {{ __('Without post.') }}
        </x-slot>

        <x-slot:actions>
            <!-- <button class="btn btn-primary">Get Started</button> -->
            <x-button label="{{ __('Courier`s announcements') }}" icon="o-clipboard-document-list" link="" class="btn btn-primary" />
        </x-slot>

        <x-slot:advertisers>
            <x-advertisers />
        </x-slot>
    </x-hero>
</div>
