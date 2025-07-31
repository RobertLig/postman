<div>
    <fieldset class="fieldset py-0">
        @if($label)
            <legend class="fieldset-legend mb-0.5">
                {{ $label }}
            </legend>
        @endif 

        @php
            $metricOrImperial = [
                ['id' => 'metric' , 'name' => 'cm/kg', 'checked' => 'checked' ],
                ['id' => 'imperial' , 'name' =>  __('inch/lbs') ],
            ];
        @endphp

        <x-radio label="{{ __('Metric or imperial') }}" wire:model="metricOrImperial" :options="$metricOrImperial" inline />

        <div class="grid gap-15 sm:grid-cols-2 sm:gap-5 xl:grid-cols-3 max-w-3xl">
            {{-- <x-dimensions.length label="{{ __('Length') }}" /> --}}
            <x-carousela class="">
                <x-slot:input>
                    <x-input label="{{ __('Length') }}" wire:model="length" placeholder="{{ __('Length') }}" clearable suffix="cm" />
                </x-slot:input>
            </x-carousela>
        </div>
    </fieldset>
</div>
