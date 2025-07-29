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

                    <div class="perspective-distant transform-3d relative">
                        <div class="transform-3d transition-transform duration-1000">
                            @php
                                $items = [0, 340, 320, 300, 280, 260, 240, 220, 200];
                            @endphp

                            {{-- @for ($i = 0; $i < 9; $i++)
                                <div class=" rotate-x-{{ $items[$i] }}">{{ $items[$i] }}</div>
                            @endfor --}}

                            <div class="absolute rotate-x-0 translate-z-50">0</div>
                            <div class="absolute rotate-x-340 translate-z-50">1</div>
                            <div class="absolute rotate-x-320 translate-z-50">2</div>
                            <div class="absolute rotate-x-300 translate-z-50">3</div>
                            <div class="absolute rotate-x-280 translate-z-50">4</div>
                        </div>
                    </div>
 
                    {{-- <x-menu-item title="Archive" wire:click.stop="" />
                    <x-menu-item title="Move" /> --}}
                </x-dropdown> 
            </div>
        blade;
    }
}
