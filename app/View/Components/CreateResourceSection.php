<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CreateResourceSection extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $label = null,

        //slots
        public mixed $subsection = null,
    )
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
                <fieldset class="fieldset py-0">
                    @if($label)
                        <legend class="fieldset-legend mb-0.5">
                            {{ $label }}
                        </legend>
                    @endif

                    @if($subsection)
                        <div {{ $subsection?->attributes->class(['']) }}> 
                            {{ $subsection }}
                        </div>
                    @endif

                    <div {{ $attributes->class(['grid gap-15 sm:gap-5']) }} >
                        {{ $slot }}
                    </div>
                   
                </fieldset>
            </div>
        blade;
    }
}
