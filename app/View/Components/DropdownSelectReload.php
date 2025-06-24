<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DropdownSelectReload extends Component
{
    public string $uuid;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $label = null,
        // named slots
        public mixed $icon = null
    ){
        $this->uuid = "mary" . md5(serialize($this)) . $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <details class="dropdown" x-data="{open: false}" @click.outside="open = false" :open="open">
                <summary {{ $attributes->class(["btn"]) }} @click.prevent="open = !open">
                    {{ $label }} 
                    {{ $icon }}
                </summary>
                <ul class="menu dropdown-content bg-base-100 rounded-box z-1 p-2 shadow-sm" @click="open = false">
                    <div wire:key="dropdown-slot-{{ $uuid }}">
                        {{ $slot }}
                    </div>
                </ul>
            </details>
        blade;
    }
}
