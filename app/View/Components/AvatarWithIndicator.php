<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AvatarWithIndicator extends Component
{
    public string $uuid;

    /**
     * @param  ?string  $image  The URL of the avatar image.
     * @param  ?string  $alt  The HTML `alt` attribute
     * @param  ?string  $placeholder  The placeholder of the avatar.
     * @param  ?string  $title  The title text displayed beside the avatar.
     * @slot  ?string  $title  The title text displayed beside the avatar.
     * @param  ?string  $subtitle  The subtitle text displayed beside the avatar.
     * @slot  ?string  $subtitle The subtitle text displayed beside the avatar.
     */

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $id = null,
        public ?string $image = '',
        public ?string $alt = '',
        public ?string $placeholder = '',
        public ?string $presenceIndicator = null, // | public bool $presenceIndicator

        // Slots
        public ?string $title = null,
        public ?string $subtitle = null
    )
    {
        $this->uuid = "robert" . md5(serialize($this)) . $id;

        //dd($indicator);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div wire:key="{{ $uuid }}" class="flex items-center gap-3">
                <div class="avatar @if(empty($image))  @endif avatar-placeholder indicator"> {{-- avatar-placeholder should be insid if statement in original avatar component, it puts letters in the center --}}

                    @if($presenceIndicator)
                        <span class="indicator-item status status-success"></span>
                    @endif

                    <div {{ $attributes->class(["w-7 rounded-full", "bg-neutral text-neutral-content" => empty($image)]) }}>
                        @if(empty($image))
                            <span class="text-xs" alt="{{ $alt }}">{{ $placeholder }}</span>
                        @else
                            <img src="{{ $image }}" alt="{{ $alt }}"/>
                        @endif
                    </div>
                </div>
                @if($title || $subtitle)
                <div>
                    @if($title)
                        <div @class(["font-semibold font-lg", is_string($title) ? '' : $title?->attributes->get('class') ]) >
                            {{ $title }}
                        </div>
                    @endif
                    @if($subtitle)
                        <div @class(["text-sm text-base-content/50", is_string($subtitle) ? '' : $subtitle?->attributes->get('class') ]) >
                            {{ $subtitle }}
                        </div>
                    @endif
                </div>
                @endif
            </div>
        blade;
    }
}
