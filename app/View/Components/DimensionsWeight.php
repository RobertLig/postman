<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DimensionsWeight extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $label = null,
        public ?array $dataCarousel = [1, 2, 3, 4, 5, 97, 98, 99, 100]
    )
    {
        //$this->dataCarousel = [1, 2, 3, 4, 5, 97, 98, 99, 100];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div>
                <fieldset class="fieldset py-0">
                    @if($label)
                        <legend class="fieldset-legend mb-0.5">
                            {{ $label }}
                        </legend>
                    @endif 

                    @php
                        $metricOrImperial = [
                            ['id' => 'metric' , 'name' => 'cm/kg' ],
                            ['id' => 'imperial' , 'name' =>  __('inch/lbs') ],
                        ];
                    @endphp

                    <x-radio label="{{ __('Metric or imperial') }}" wire:model="metricOrImperial" :options="$metricOrImperial" inline wire:click="changeSuffix()" />

                    <div class="grid gap-15 sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-3xl">
                        {{-- <x-dimensions.length label="{{ __('Length') }}" /> --}}
                        <x-carousela class="" :data-carousel="$dataCarousel" input="1" total-value="100" start-value="1" input-id="length" >
                            <x-slot:input-element>
                                <x-input label="{{ __('Length') }}" wire:model.live="length" placeholder="{{ __('Length') }}" clearable > {{-- x-model="inputPlaceholder" --}}
                                    <x-slot:append>
                                        <livewire:announcement.measure-suffix />
                                    </x-slot:append>
                                </x-input> 
                            </x-slot:input-element>
                        </x-carousela>
                    </div>
                </fieldset>
            </div>
        blade;
    }
}
