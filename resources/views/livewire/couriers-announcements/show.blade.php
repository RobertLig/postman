<div>
    <x-header title="{{ __('Couriers` announcement') }}" subtitle="{{ __('See ad details.') }}" separator />

    <div class="text-3xl font-bold">{{ $thing }}</div>

    <div class="my-5 ">{{ $description }}</div> 

    <x-show-weight-length-width-height weight="{{ $weight }}" dimension-length="{{ $dimensionLength }}" width="{{ $width }}" height="{{ $height }}" kg="{{ $kg }}" cm="{{ $cm }}"/>

    <x-show-from-to-place-date-time posting-place="{{ $postingPlace }}" reception-place="{{ $receptionPlace }}" posting-day="{{ $postingDay }}" reception-day="{{ $receptionDay }}"
        posting-month="{{ $postingMonth }}" reception-month="{{ $receptionMonth }}" posting-year="{{ $postingYear }}" reception-year="{{ $receptionYear }}" posting-hour="{{ $postingHour }}" 
        reception-hour="{{ $receptionHour }}" posting-minute="{{ $postingMinute }}" reception-minute="{{ $receptionMinute }}" /> 

    <div class="divider"></div>

    @if(auth()->user())
        <div class="text-xl font-medium mt-10">{{ __('Advertiser') }}</div>

        <x-list-item :item="$courier->user" class="mt-3" > 
            <x-slot:avatar>

                <x-avatar :image="$courier->user->getAvatar()" 
                    placeholder="{{ $courier->user->initials() }}" class="!w-10" />

            </x-slot:avatar>

            <x-slot:sub-value>
                <div>{{ __($courier->user->gender) }}</div>

                @if($courier->user->age)
                    <div>{{ __($courier->user->age) }} {{ __('years') }}</div>
                @endif
            </x-slot:sub-value>

        </x-list-item>

        <div class="mb-5"></div>

        @can('talk', $courier->user) 
            <livewire:chat :user="$courier->user" :announcement="$courier" /> 
        @endcan 

    @endif 

</div>
