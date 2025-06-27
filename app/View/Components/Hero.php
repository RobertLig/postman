<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Hero extends Component
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
            <div class="p-13 bg-base-200 min-h-min">
                <div class="">
                    <div class="">
                        <h1 class="text-6xl font-bold">Ship faster</h1>
                        <p class="py-6">
                            Without post.
                        </p>
                        <button class="btn btn-primary">Get Started</button>
                    </div>
                </div>
            </div>
        blade;
    }
}
