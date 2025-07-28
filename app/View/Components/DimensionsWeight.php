<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DimensionsWeight extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $label = null,
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

                    <div class="grid gap-15 sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-3xl">
                        <x-dimensions.length label="{{ __('Length') }}" />
                    </div>
                </fieldset>
            </div>
        blade;
    }
}
