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
    )
    {
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
                <div class="grid sm:grid-flow-col gap-x-1 w-fit ">
                    @if($weight)
                    <div class="mb-3 sm:mb-0 sm:me-2 ">{{ $weight }} <span>{{ $kg }}</span>,</div>
                    @endif

                    @if($dimensionLength)
                    <div class="">{{ $dimensionLength }} <span>{{ $cm }}</span></div> 
                    @endif

                    @if($dimensionLength && $width)
                    <div class="text-center">x</div>
                    @endif

                    @if($width)
                    <div class="">{{ $width }} <span>{{ $cm }}</span></div>
                    @endif

                    @if(($dimensionLength || $width) && $height)
                    <div class="text-center">x</div>
                    @endif

                    @if($height)
                    <div class="">{{ $height }} <span>{{ $cm }}</span></div>
                    @endif
                </div>
                @endif
            </div>
        blade;
    }
}
