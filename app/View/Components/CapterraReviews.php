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
            {{ $slot }}
        blade;
    }
}
