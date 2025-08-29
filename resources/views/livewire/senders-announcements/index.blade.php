<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Senders` announcements')]
class extends Component {
    //
}; ?>

<div>
    <x-header title="{{ __('Senders` announcements') }}" subtitle="{{ __('These are ads from people who would like to send something.') }}" separator >

        <x-slot:actions>
            @if(auth()->user())
                <x-button label="{{ __('Create a new ad') }}" responsive icon="o-plus" link="{{ route('senders-announcements.create') }}" class="btn btn-primary btn-sm " />
            @endif

            <x-button label="{{ __('Filters') }}" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions>

    </x-header>

    <div class="grid sm:grid-cols-2 sm:gap-x-5">
        
    </div>

    {{-- @if(auth()->user())
        <x-button label="{{ __('Create a new ad') }}" icon="o-plus" link="{{ route('senders-announcements.create') }}" class="btn btn-primary btn-sm " />
    @endif --}}


</div>
