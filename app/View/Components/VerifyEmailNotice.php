<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VerifyEmailNotice extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        // named slots
        public ?string $subtitle = null,
        public ?string $title = null,
        public ?string $section = null,
        public ?string $actions = null
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div {{ $attributes->class(['flex  ']) }}>    
                <div class="max-w-md px-13 py-9 border border-base-content">
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

                    @if($section)
                        <p {{ $section?->attributes->class(['py-6 mt-2']) }}>
                            {{ $section }}
                        </p>
                    @endif

                    @if($actions)
                        <div {{ $actions?->attributes->class(['']) }}>
                            {{ $actions }}
                        </div>
                    @endif
                </div>
            </div>
        blade;
    }
}
