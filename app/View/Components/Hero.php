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
    public function __construct(
        // named slots
        public ?string $subtitle = null,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div class="px-13 py-10 bg-base-200 min-h-min">
                <div class="">
                    <div class="">
                        <h1 {{ $attributes->class(['font-bold']) }}>{{ $slot }}</h1>

                        @if($subtitle)
                            <p {{ $subtitle?->attributes->class(['py-6']) }}>
                                {{ $subtitle }}
                            </p>
                        @endif

                        <button class="btn btn-primary">Get Started</button>
                    </div>
                </div>
            </div>
        blade;
    }
}
