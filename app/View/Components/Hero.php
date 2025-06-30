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
        public ?string $title = null
        //public ?string $actionsAdvertisersReviews = null
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div {{ $attributes->class(['flex px-13 py-9 min-h-min']) }}>    
                <div>
                    @if($title)
                        <h1 {{ $title?->attributes->class(['font-bold']) }}>
                            {{ $title }}
                        </h1>
                    @endif
                    
                    @if($subtitle)
                        <p {{ $subtitle?->attributes->class(['py-6 mt-2']) }}>
                            {{ $subtitle }}
                        </p>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        blade;
    }
}
