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

                    <div {{ $attributes->class(['h-50 perspective-distant relative']) }} >
                        <div class="absolute top-17 transform-3d transition-transform duration-1000 ">
                            @php
                                $items = [0, 340, 320, 300, 280, 260, 240, 220, 200, 180, 160, 140, 120, 100, 80, 60, 40, 20];
                            @endphp

                            @for ($i = 0; $i < 18; $i++)
                                <div class="p-1 bg-amber-300 absolute" style="transform: rotateX({{ $items[$i] }}deg) translateZ(80px)">{{ $items[$i] }}</div>
                            @endfor 

                        </div>
                    </div>
 
                    {{-- <x-menu-item title="Archive" wire:click.stop="" />
                    <x-menu-item title="Move" /> --}}
                </x-dropdown> 
            </div>
        blade;
    }
}
