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
                        <svg xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-10 h-10 text-primary shrink-0">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 16.5V7.5a2.25 2.25 0 0 0-1.133-1.957l-6.75-3.857a2.25 2.25 0 0 0-2.234 0l-6.75 3.857A2.25 2.25 0 0 0 3 7.5v9a2.25 2.25 0 0 0 1.133 1.957l6.75 3.857a2.25 2.25 0 0 0 2.234 0l6.75-3.857A2.25 2.25 0 0 0 21 16.5Z" />
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
                                d="M12 3v1.5m0 0a2.25 2.25 0 1 0 0 4.5m0-4.5a2.25 2.25 0 1 1 0 4.5m-7.5 3h15l-1.5 9h-12l-1.5-9Z" />
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
