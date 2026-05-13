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
        public ?array $dataCarousel = [1, 2, 3, 4, 5, 97, 98, 99, 100],
        public ?array $textValues = null, //[ __('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December')],
    ) {
        //$this->dataCarousel = [1, 2, 3, 4, 5, 97, 98, 99, 100];

        //dd($this->textValues);
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

                    <x-hr target="metricOrImperial" />

                    <div {{ $attributes->class(['grid max-w-3xl']) }}> 
                        <div>
                            <x-input label="{{ __('Length') }}" wire:model="dimensionLength" placeholder="{{ __('Length') }}" clearable > 
                                <x-slot:append>
                                    <livewire:announcement.measure-suffix />
                                </x-slot:append>
                            </x-input> 
                        
                            <x-hr target="dimensionLength" /> 
                        </div> 
                        
                        <div>
                            <x-input label="{{ __('Width') }}" wire:model="width" placeholder="{{ __('Width') }}" clearable > 
                                <x-slot:append>
                                    <livewire:announcement.measure-suffix />
                                </x-slot:append>
                            </x-input> 
                        
                            <x-hr target="width" /> 
                        </div>  
                            
                        <div>
                            <x-input label="{{ __('Height') }}" wire:model="height" placeholder="{{ __('Height') }}" clearable > 
                                <x-slot:append>
                                    <livewire:announcement.measure-suffix />
                                </x-slot:append>
                            </x-input> 
                        
                            <x-hr target="height" /> 
                        </div> 
                            
                        <div>
                            <x-input label="{{ __('Weight') }}" wire:model="weight" placeholder="{{ __('Weight') }}" clearable > 
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
