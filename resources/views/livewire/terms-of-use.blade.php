<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <x-header title="{{ __('Terms of use') }}" separator />

    <p> 
        {{ __("The owner of the ") }}
        {{-- app brand --}}
        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/') }}" wire:navigate>
            <!-- Hidden when collapsed -->
            <div class="hidden-when-collapsed">
                <div class="flex items-center gap-2 w-fit">
                    <x-icons.app-logo  /> {{-- width="48" height="48" --}}
                    <span class="font-bold text-xl bg-clip-text"> {{-- text-3xl --}}
                        Postman
                    </span>
                </div>
            </div>
        
            <!-- Display when collapsed (doesn't work-->
            {{-- <div class="display-when-collapsed hidden mx-5 mt-5 mb-1 h-[28px]">
                <x-icons.app-logo  /> 
            </div> --}}
        </a>
        {{ __(" website is not liable for any damages resulting from its use.") }}
    </p>
</div>
