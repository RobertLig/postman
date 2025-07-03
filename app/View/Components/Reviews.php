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
        /*Show only 3 reviews at time. Fetch 2 Google and 1 Capterra reviews using pagination(?). Mix them together (in one array?). 
        Show them using pagination. If there is no more Google reviews, continue showing 3 Capterra's. And vice versa.
        It may be interractive component because of pagination, so transform it into livewire component.*/ 
    }

    /**
     * Get the view / contents that represent the component. Only show when Google or Capterra reviews are available.
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

                        <!-- only for testing -->
                        <x-stars-substitute class="py-2">
                            <x-icon name="o-star" class="w-5 h-5 -mx-0.5" />
                        </x-stars-substitute>

                        <div class="font-bold">(262+ {{ __('reviews') }})</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <x-icon name="o-arrow-up-right" class="w-7 h-7 font-semibold text-2xl" label="Capterra" />

                        <!-- only for testing -->
                        <x-stars-substitute class="py-2">
                            <x-icon name="o-star" class="w-5 h-5 -mx-0.5" />
                        </x-stars-substitute>
                        
                        <div class="font-bold">(123+ {{ __('reviews') }})</div>
                    </div>
                </div>

                <div class="grid gap-15 sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-2xl mx-auto mt-10">
                    @for ($i = 0; $i < 3; $i++)
                        {{ $slot }}
                    @endfor
                </div>
            </div>
        blade;
    }
}
