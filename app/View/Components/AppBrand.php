<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
                <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeUrl('/') }}" wire:navigate>
                    
                    <div {{ $attributes->class(["hidden-when-collapsed"]) }}>
                        <div class="flex items-center gap-2 w-fit">
                            <x-icons.app-logo width="48" height="48" /> 
                            <span class="font-bold text-3xl bg-clip-text"> <!-- me-3 bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text text-transparent -->
                                Postman
                            </span>
                        </div>
                    </div>
                </a>
            blade;
    }
}
