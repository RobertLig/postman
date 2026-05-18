<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Cookie policy')] class extends Component {
    public string $metaDescription;

    public function mount()
    {
        $this->metaDescription = __('Cookie policy');
    }
}; ?>

<div>
    <x-header title="{{ __('Cookie policy') }}" separator />
</div>
