<?php

namespace App\View\Components\Weight;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Weight extends Component
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
<div>
    <!-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh -->
</div>
blade;
    }
}
