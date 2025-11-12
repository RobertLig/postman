<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('Privacy policy')]
class extends Component {
    //
}; ?>

<div>
    <x-header title="{{ __('Privacy policy') }}" separator />
</div>
