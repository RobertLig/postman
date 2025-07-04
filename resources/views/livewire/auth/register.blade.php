<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Register')]
class extends Component {
    //
}; ?>

<div>
    <x-header title="{{ __('Register') }}" separator />

    <x-form wire:submit="save">
        <x-input label="{{ __('Name') }}" wire:model="name" />
 
        <x-input label="{{ __('E-Mail Address') }}" wire:model="email" />
 
        <x-slot:actions>
            <x-button label="{{ __('Save') }}" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
