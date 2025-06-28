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
        public mixed $actions = null,
        public mixed $advertisers = null,
        public mixed $reviews = null
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div class="px-13 py-9 bg-base-200 min-h-min">
                <div class="">
                    <div class="">
                        <h1 {{ $attributes->class(['font-bold leading-12']) }}>{{ $slot }}</h1>

                        @if($subtitle)
                            <p {{ $subtitle?->attributes->class(['py-6']) }}>
                                {{ $subtitle }}
                            </p>
                        @endif

                        <div class="flex items-center">
                            {{ $actions }}

                            <div class="flex flex-col justify-between ps-3 h-10">
                                {{ $advertisers }}
                                {{ $reviews }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        blade;
    }
}
