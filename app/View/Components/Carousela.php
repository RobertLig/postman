<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Carousela extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public mixed $input,
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
                <x-dropdown>
                    <x-slot:trigger>
                        {{ $input }}
                    </x-slot:trigger>
 
                    <x-menu-item title="Archive" />
                    <x-menu-item title="Move" />
                </x-dropdown> 
            </div>
        blade;
    }
}
