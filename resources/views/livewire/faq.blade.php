<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('FAQ')]
class extends Component {
    //
}; ?>

<div>
    <x-header title="{{ __('Frequently Asked Questions') }}" subtitle="{{ __('These are commonly asked questions.') }}" separator />
    
    <!-- use Collapse mary ui component -->
</div>
