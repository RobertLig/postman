<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;

new #[Title('FAQ')]
class extends Component {
    //
}; ?>

<div>
    <x-header title="{{ __('Frequently Asked Questions') }}" subtitle="{{ __('These are commonly asked questions.') }}" separator />

    <div>
        {{ __('If you find any errors, please let us know by submitting them via the form on the ') }} <a href="{{ route('contact') }}" class="link ">{{ __('contact page.') }} </a> 
    </div>
    
    <!-- use Collapse mary ui component -->
</div>
