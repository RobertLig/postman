<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <x-hero>
        <span class="text-6xl">Ship faster</span>

        <x-slot:subtitle>
            Without post.
        </x-slot>

        <x-slot:actions>
            <!-- <button class="btn btn-primary">Get Started</button> -->
            <x-button label="{{ __('Courier`s announcements') }}" class="btn btn-primary" />
        </x-slot>
    </x-hero>
</div>
