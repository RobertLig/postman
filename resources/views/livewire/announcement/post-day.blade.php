{{--component not working. Couldn't reset properties on Alpine with $wire.entangle() during livewire server roundtrip. Issue not solved --}}
<x-carousela class="" :data-carousel="$dataDay" input="{{ $input }}" total-value="{{ $totalValue }}" start-value="1" model-name="postingDay" is-live="true"  
    prefix-zero="false" :text-values="$textValuesDay" > 
                            
    <x-slot:input-element>
        <x-input label="{{ __('Day') }}" wire:model.live="postingDay" placeholder="{{ __('Day') }}" clearable /> 
    </x-slot:input-element>

    <x-slot:progress>
        <x-hr target="postingDay" /> 
    </x-slot:progress> 
</x-carousela> 

