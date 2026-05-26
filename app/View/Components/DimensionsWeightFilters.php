<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DimensionsWeightFilters extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $label = null
    ) {}

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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                            </svg>    

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

                    <x-hr target="metricOrImperial" />

                    <div {{ $attributes->class(['grid max-w-3xl']) }}> 
                        <div>
                            <x-input label="{{ __('Length') }}" wire:model.live="dimensionLength" placeholder="{{ __('Length') }}" clearable icon="o-arrow-right" > 
                                <x-slot:append>
                                    <livewire:announcement.measure-suffix />
                                </x-slot:append>
                            </x-input> 
                        
                            <x-hr target="dimensionLength" /> 
                        </div> 
                  
                        <div>
                            <x-input label="{{ __('Width') }}" wire:model.live="width" placeholder="{{ __('Width') }}" clearable icon="o-arrow-up-left" > 
                                <x-slot:append>
                                    <livewire:announcement.measure-suffix />
                                </x-slot:append>
                            </x-input> 
                        
                            <x-hr target="width" /> 
                        </div> 
                        
                        <div>
                            <x-input label="{{ __('Height') }}" wire:model.live="height" placeholder="{{ __('Height') }}" clearable icon="o-arrow-up" > 
                                <x-slot:append>
                                    <livewire:announcement.measure-suffix />
                                </x-slot:append>
                            </x-input> 
                        
                            <x-hr target="height" /> 
                        </div> 
                        
                        <div>
                            <x-input label="{{ __('Weight') }}" wire:model.live="weight" placeholder="{{ __('Weight') }}" clearable icon="o-scale" > 
                                <x-slot:append>
                                    <livewire:announcement.weight-suffix />
                                </x-slot:append>
                            </x-input> 
                        
                            <x-hr target="weight" /> 
                        </div> 
                    </div>
                </fieldset>
            </div>
        blade;
    }
}
