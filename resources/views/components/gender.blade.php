<div>
    @php
        $users = [
            ['id' => 'male' , 'name' => __('Male')],
            ['id' => 'female' , 'name' => __('Female')],
        ];
    @endphp
 
    <x-radio label="{{ __('Gender') }}" wire:model="gender" wire:click="resetGender" :options="$users" />

    <x-hr target="resetGender" />
</div>