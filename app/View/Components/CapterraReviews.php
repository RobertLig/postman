<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CapterraReviews extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //fetch capterra reviews
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <!-- Below is emergency option in case capterra reviews can't be fetched -->
            <div {{ $attributes->class(['min-w-25 ps-3']) }}>
                @for ($i = 0; $i < 5; $i++)
                    <x-icon name="o-star" class="w-4 h-4 -mx-0.5" />
                @endfor
            </div> 
        blade;
    }
}
