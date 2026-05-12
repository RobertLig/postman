<div>
    <x-header title="{{ $this->supportsImages() ? __('Sender` announcement') : __('Couriers` announcement') }}"
        subtitle="{{ __('See ad details.') }}" separator />

    <div class="text-3xl font-bold">{{ $thing }}</div>

    @if ($this->supportsImages())
        @php
            if ($announcement->library->count()) {
                $slides = [];

                foreach ($announcement->library as $image) {
                    $slides[] = ['image' => $image['url']]; //https://picsum.photos/500/200?random=1
                }
            }
        @endphp

        @if ($announcement->library->count())
            <x-robert-carousel :slides="$slides" class="mt-3" /> {{-- x-carousel --}}
        @endif
    @endif

    <div class="my-5 ">{{ $description }}</div>

    <x-show-weight-length-width-height weight="{{ $weight }}" dimension-length="{{ $dimensionLength }}"
        width="{{ $width }}" height="{{ $height }}" kg="{{ $kg }}" cm="{{ $cm }}" />

    <x-show-from-to-place-date-time posting-place="{{ $postingPlace }}" reception-place="{{ $receptionPlace }}"
        posting-day="{{ $postingDay }}" reception-day="{{ $receptionDay }}" posting-month="{{ $postingMonth }}"
        reception-month="{{ $receptionMonth }}" posting-year="{{ $postingYear }}"
        reception-year="{{ $receptionYear }}" posting-hour="{{ $postingHour }}"
        reception-hour="{{ $receptionHour }}" posting-minute="{{ $postingMinute }}"
        reception-minute="{{ $receptionMinute }}" />

    <div class="divider"></div>

    @if (auth()->user())
        <div class="text-xl font-medium mt-10">{{ __('Advertiser') }}</div>

        <x-list-item :item="$announcement->user" class="mt-3">
            <x-slot:avatar>

                <x-avatar :image="$announcement->user->getAvatar()" placeholder="{{ $announcement->user->initials() }}" class="!w-10" />

            </x-slot:avatar>

            <x-slot:sub-value>
                <div>{{ __($announcement->user->gender) }}</div>

                @if ($announcement->user->age)
                    <div>{{ __($announcement->user->age) }} {{ __('years') }}</div>
                @endif
            </x-slot:sub-value>

        </x-list-item>

        <div class="mb-5"></div>

        {{-- @can('talk', $announcement->user)
            <livewire:chat :user="$announcement->user" :announcement="$announcement" />
        @endcan --}}

    @endif

</div>
