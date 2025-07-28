<?php

namespace App\View\Components\Dimensions;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Length extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $label = null,
    )
    {
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div > {{-- class="bg-amber-500" --}}
                @if($label)
                    <legend class="fieldset-legend mb-0.5">
                        {{ $label }}
                    </legend>

                    <x-dropdown>
                        <x-slot:trigger>
                            <x-button icon="o-bell" class="btn-circle" />
                        </x-slot:trigger>
 
                        <x-menu-item title="Archive" />
                        <x-menu-item title="Move" />
                    </x-dropdown>
                @endif
            </div>
        blade;
    }
}
