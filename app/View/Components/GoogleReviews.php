<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class GoogleReviews extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //fetch google reviews from Places API
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div>
                <!-- Below is emergency option in case google reviews can't be fetched -->
                {{ $slot }}
            </div>
        blade;
    }
}
