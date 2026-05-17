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

                <div class="grid gap-15 md:grid-cols-2 md:gap-5 max-w-lg mx-auto mt-10">
                    
                    <x-widgets.embedsocial-widget /> 

                    <x-widgets.trustpilot-widget />

                </div>

            </div>
        blade;
    }
}
