<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowFromToPlaceDateTime extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $postingPlace,
        public string $receptionPlace,
        public string $postingAt,
        public string $receptionAt
    ) {
        //dd($postingAt);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div>
                <div class="mt-10 grid sm:grid-cols-2 gap-3 bg-base-200 p-2 rounded-lg">
                    <div class="flex  gap-3 ">
                        <x-badge :value="__('From')" class="badge-soft" />
                        <div class="wrap-normal">{{ $postingPlace }}</div>
                    </div>

                    <div class="flex  gap-3 ">
                        <x-badge :value="__('on')" class="badge-soft" />
                        <div>{{ $postingAt }}</div>
                    </div>
                </div>

                <div class="mt-10 grid sm:grid-cols-2 gap-3 bg-base-200 p-2 rounded-lg">
                    <div class="flex  gap-3 ">
                        <x-badge :value="__('To')" class="badge-soft" />
                        <div class="wrap-normal">{{ $receptionPlace }}</div>
                    </div>

                    <div class="flex  gap-3 ">
                        <x-badge :value="__('on')" class="badge-soft" />
                        <div>{{ $receptionAt }}</div>
                    </div>
                </div>
            </div>
        blade;
    }
}
