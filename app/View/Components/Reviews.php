<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Reviews extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct() {}


    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div class="mt-30">

                <h1 class="leading-11 text-3xl font-bold text-center">
                    {{ __('What people are saying') }}
                </h1>

                <p class="text-center text-base-content/70 mt-4 max-w-2xl mx-auto">
                    {{ __('Experiences shared by our community.') }}
                </p>

                <div class="mt-15">
                    {{-- <livewire:testimonials-carousel /> --}}
                </div>

                <div class="mt-12 max-w-lg mx-auto">
                    <x-external-review-card />
                </div>

            </div>
        blade;
    }
}
