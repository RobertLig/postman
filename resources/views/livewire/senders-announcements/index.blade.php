<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Senders` announcements')]
class extends Component {
    //
}; ?>

<div>
    <x-header title="{{ __('Senders` announcements') }}" subtitle="{{ __('These are ads from people who would like to send something.') }}" separator />

    @if(auth()->user())
        <x-button label="{{ __('Create a new ad') }}" icon="o-plus" link="{{ route('senders-announcements.create') }}" class="btn btn-primary btn-sm " />
     @endif


</div>
