<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Messages')]
class extends Component {
    //
}; ?>

<div>
    <x-header title="{{ __('Messages') }}" subtitle="{{ __('Engage in public chat or choose somebody for private one.') }}" separator />

    <div class="h-130 bg-amber-500">
        
    </div>

    <x-form wire:submit="save" no-separator>
        <x-input label="{{ __('Send a message') }}" wire:model.live="newMessage" placeholder="{{ __('Message') }}" icon="o-chat-bubble-left-right" clearable />

        <x-slot:actions>
            <x-button label="{{ __('Send') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
