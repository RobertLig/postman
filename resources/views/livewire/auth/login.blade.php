<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Login')]
class extends Component {
    //
}; ?>

<div>
    <x-header title="{{ __('Login') }}" separator />

    <x-form wire:submit="save">
        <x-input label="{{ __('E-Mail Address') }}" wire:model="email" placeholder="{{ __('mail@site.com') }}" icon="o-envelope"  clearable />

        <x-password label="{{ __('Password') }}" wire:model="password" placeholder="{{ __('Password') }}"  clearable />

        <x-checkbox label="{{ __('Remember me') }}" class=""/>

        <x-slot:actions>
            <x-button label="{{ __('Login') }}" icon="o-arrow-right-end-on-rectangle" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
