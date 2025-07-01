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
    public function __construct(
        
    ) {
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div class="mt-30">
                <h1 class="leading-11 text-3xl font-bold text-center">
                    {{ __('What people are saying') }}
                </h1>

                <div class="grid gap-15 md:grid-cols-2 md:gap-5 max-w-md mx-auto mt-10">
                    <div class="flex flex-col items-center">
                        <div class="font-semibold text-2xl">Google</div>
                        <x-rating wire:model="ranking0" class="rating-sm py-5"/>
                        <div class="font-bold">(262+ {{ __('reviews') }})</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <x-icon name="o-arrow-up-right" class="w-7 h-7 font-semibold text-2xl" label="Capterra" />
                        <x-rating wire:model="ranking0" class="rating-sm py-5"/>
                        <div class="font-bold">(123+ {{ __('reviews') }})</div>
                    </div>
                </div>

                <div class="grid gap-15 md:grid-cols-2 md:gap-5 max-w-md mx-auto mt-10">
                
                </div>
            </div>
        blade;
    }
}
