<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ShowWeightLengthWidthHeight extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $weight = null,
        public ?string $dimensionLength = null,
        public ?string $width = null,
        public ?string $height = null,
        public string $kg = 'kg',
        public string $cm = 'cm',
    ) {
        //dd($metricOrImperial);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div>
                @php
                    $options = [
                        ['id' => 'metric' , 'name' => 'cm/kg' ],
                        ['id' => 'imperial' , 'name' =>  __('inch/lbs') ],
                    ];
                @endphp
                
                @if($weight || $dimensionLength || $width || $height)
                    <x-radio label="{{ __('Metric or imperial') }}" wire:model.live="metricOrImperial" :options="$options" inline  /> 
                @endif

                <x-hr target="metricOrImperial" />

                @if($weight || $dimensionLength || $width || $height)
                <div class="flex flex-col gap-4">

                    {{-- Dimensions --}}
                    @if($dimensionLength || $width || $height)
                    <div class="flex items-start gap-3">
                        
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-primary shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>


                        <div>
                            <div class="font-semibold">
                                {{ __('Package dimensions') }}
                            </div>

                            <div class="text-sm opacity-80">
                                @if($dimensionLength)
                                    <div>{{ __('Length') }}: {{ $dimensionLength }} {{ $cm }}</div>
                                @endif

                                @if($width)
                                    <div>{{ __('Width') }}: {{ $width }} {{ $cm }}</div>
                                @endif

                                @if($height)
                                    <div>{{ __('Height') }}: {{ $height }} {{ $cm }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Weight --}}
                    @if($weight)
                    <div class="flex items-center gap-3">
                        
                        <svg xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-10 h-10 text-primary shrink-0">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 2v2
                                    M12 4h-1.5a.75.75 0 0 0 0 1.5H12m0 0h1.5a.75.75 0 0 0 0-1.5H12
                                    M12 4v9
                                    M5 13l2 4h-4l2-4Z
                                    M19 13l2 4h-4l2-4Z
                                    M5 13h14
                                    M10 21h4
                                    M12 21v-2" />
                        </svg>

                        <div>
                            <div class="font-semibold">
                                {{ __('Weight') }}
                            </div>

                            <div class="text-sm opacity-80">
                                {{ $weight }} {{ $kg }}
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                @endif
            </div>
        blade;
    }
}
