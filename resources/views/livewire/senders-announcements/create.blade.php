<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Create senders` announcement')]
class extends Component {
    public $thing;
}; ?>

<div>
    <x-header title="{{ __('Create senders` announcement') }}" subtitle="{{ __('If you would like to send something, please fill out the form and post an ad.') }}" separator />
     
    <x-form wire:submit="save">
        <x-input label="{{ __('A thing') }}" wire:model="thing" placeholder="{{ __('Thing') }}" icon="o-question-mark-circle"  clearable /> 

        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
