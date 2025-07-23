<div>
    @php
        $ages = [
            ['id' => '< 20' , 'name' => __('< 20')],
            ['id' => '20 to 29' , 'name' => __('20 to 29')],
            ['id' => '30 to 39' , 'name' => __('30 to 39')],
            ['id' => '40 to 49' , 'name' => __('40 to 49')],
            ['id' => '50 to 59' , 'name' => __('50 to 59')],
            ['id' => '60 to 69' , 'name' => __('60 to 69')],
            ['id' => '70 to 79' , 'name' => __('70 to 79')],
            ['id' => '80 to 89' , 'name' => __('80 to 89')],
            ['id' => '> 90' , 'name' => __('> 90')]
        ];
    @endphp
 
    <x-radio label="{{ __('Age') }}" wire:model="age" wire:click="resetAge" :options="$ages" />

    <x-hr target="resetAge" />
</div>