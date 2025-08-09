<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
//use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\App;

class DimensionsWeight extends Component
{
    

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $label = null,
        public ?array $dataCarousel = [1, 2, 3, 4, 5, 97, 98, 99, 100],
        public ?array $textValues = null,
        public ?array $en = null, //['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        public ?array $pl = null, //['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'],
    )
    {
        //$this->dataCarousel = [1, 2, 3, 4, 5, 97, 98, 99, 100];
        //dd(LaravelLocalization::getCurrentLocale());

        if($this->en && $this->pl)
        {
            $this->textValues = App::currentLocale() == 'en' ? $this->en : $this->pl;
        }

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

                    <div class="grid gap-15 sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-3xl">
                        {{-- <x-dimensions.length label="{{ __('Length') }}" />;  --}}
                        <x-carousela class="" :data-carousel="$dataCarousel" input="1" total-value="100" start-value="1" model-name="dimensionLength" is-live="true"  
                            prefix-zero="false" :text-values="$textValues" > {{-- set-property-method="setLength" --}}
                            
                            <x-slot:input-element>
                                <x-input label="{{ __('Length') }}" wire:model.live="dimensionLength" placeholder="{{ __('Length') }}" clearable > {{-- x-model="inputPlaceholder" --}}
                                    <x-slot:append>
                                        <livewire:announcement.measure-suffix />
                                    </x-slot:append>
                                </x-input> 
                            </x-slot:input-element>

                            <x-slot:progress>
                                <x-hr target="dimensionLength" /> {{-- setLength; can be set to both property name and action name --}}
                            </x-slot:progress> 
                        </x-carousela>

                        <x-carousela class="" :data-carousel="$dataCarousel" input="1" total-value="100" start-value="1" model-name="width" is-live="true"  
                            prefix-zero="false" :text-values="$textValues" > {{-- set-property-method="setLength" --}}
                            
                            <x-slot:input-element>
                                <x-input label="{{ __('Width') }}" wire:model.live="width" placeholder="{{ __('Width') }}" clearable > {{-- x-model="inputPlaceholder" --}}
                                    <x-slot:append>
                                        <livewire:announcement.measure-suffix />
                                    </x-slot:append>
                                </x-input> 
                            </x-slot:input-element>

                            <x-slot:progress>
                                <x-hr target="width" /> {{-- setLength; can be set to both property name and action name --}}
                            </x-slot:progress> 
                        </x-carousela>

                        <x-carousela class="" :data-carousel="$dataCarousel" input="1" total-value="100" start-value="1" model-name="height" is-live="true"  
                            prefix-zero="false" :text-values="$textValues" > {{-- set-property-method="setLength" --}}
                            
                            <x-slot:input-element>
                                <x-input label="{{ __('Height') }}" wire:model.live="height" placeholder="{{ __('Height') }}" clearable > {{-- x-model="inputPlaceholder" --}}
                                    <x-slot:append>
                                        <livewire:announcement.measure-suffix />
                                    </x-slot:append>
                                </x-input> 
                            </x-slot:input-element>

                            <x-slot:progress>
                                <x-hr target="height" /> {{-- setLength; can be set to both property name and action name --}}
                            </x-slot:progress> 
                        </x-carousela>

                        <x-carousela class="" :data-carousel="$dataCarousel" input="1" total-value="100" start-value="1" model-name="weight" is-live="true"  
                            prefix-zero="false" :text-values="$textValues" > {{-- set-property-method="setLength" --}}
                            
                            <x-slot:input-element>
                                <x-input label="{{ __('Weight') }}" wire:model.live="weight" placeholder="{{ __('Weight') }}" clearable > {{-- x-model="inputPlaceholder" --}}
                                    <x-slot:append>
                                        <livewire:announcement.measure-suffix />
                                    </x-slot:append>
                                </x-input> 
                            </x-slot:input-element>

                            <x-slot:progress>
                                <x-hr target="weight" /> {{-- setLength; can be set to both property name and action name --}}
                            </x-slot:progress> 
                        </x-carousela>
                    </div>
                </fieldset>
            </div>
        blade;
    }
}
