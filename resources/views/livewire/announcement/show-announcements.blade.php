<div>
    <x-header title="{{ $this->supportsImages() ? __('📦 Senders` announcements') : __('🚚 Couriers` announcements') }}"
        subtitle="{{ $this->supportsImages()
            ? __('These are ads from people who would like to send something.')
            : __('These are ads from people who would like to deliver something for someone.') }}"
        separator>

        <x-slot:actions>
            @if (auth()->user())
                <x-button label="{{ __('Create a new ad') }}" responsive icon="o-plus"
                    link="{{ $this->supportsImages() ? route('senders.create') : route('couriers.create') }}"
                    class="btn btn-primary" />
            @endif

            <x-button label="{{ __('Filters') }}" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions>

    </x-header>

    {{-- badges for filters --}}
    <div @class([
        'flex',
        'flex-wrap',
        'gap-2',
        'mb-5' => $this->hasActiveFilters(),
    ])>

        @if ($thing)
            <x-filter-badge label="{{ __('thing') }}" clear="thing" />
        @endif

        @if ($description)
            <x-filter-badge label="{{ __('description') }}" clear="description" />
        @endif

        @if ($metricOrImperial)
            <x-filter-badge label="{{ __('metric or imperial') }}" clear="metricOrImperial" />
        @endif

        @if ($dimensionLength)
            <x-filter-badge label="{{ __('length') }}" clear="dimensionLength" />
        @endif

        @if ($width)
            <x-filter-badge label="{{ __('width') }}" clear="width" />
        @endif

        @if ($height)
            <x-filter-badge label="{{ __('height') }}" clear="height" />
        @endif

        @if ($weight)
            <x-filter-badge label="{{ __('weight') }}" clear="weight" />
        @endif

        @if ($postingPlace)
            <x-filter-badge label="{{ __('posting place') }}" clear="postingPlace" />
        @endif

        @if ($receptionPlace)
            <x-filter-badge label="{{ __('reception place') }}" clear="receptionPlace" />
        @endif

        @if ($posting_at)
            <x-filter-badge label="{{ __('posting date') }}" clear="posting_at" />
        @endif

        @if ($reception_at)
            <x-filter-badge label="{{ __('reception date') }}" clear="reception_at" />
        @endif

        @if ($this->hasActiveFilters())
            <x-button icon-right="o-x-mark" class="w-full btn-sm btn-secondary rounded-xl" :label="__('Cancel All')"
                wire:click="removeFilters" responsive />
        @endif
    </div>

    <div class="grid sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5">
        @foreach ($announcements as $announcement)
            @php
                $translation = $announcement->translate($language->id);
            @endphp

            <x-card :title="$translation->thing" shadow separator progress-indicator="delete({{ $announcement->id }})"
                :key="$announcement->id">
                <div class="flex items-center justify-between gap-3">
                    <x-badge :value="__('From')" class="badge-soft" />
                    <div class="font-medium">{!! Str::limit($translation->posting_place, 30) !!}</div>
                </div>

                <div class="flex items-center justify-between gap-3 mt-2">
                    <x-badge :value="__('on')" class="badge-soft" />
                    <div>{!! Str::limit($announcement->posting_at?->locale(app()->getLocale())->translatedFormat('D, d F Y, H:i'), 30) !!}</div>
                </div>

                <div class="flex items-center justify-between gap-3 mt-2">
                    <x-badge :value="__('To')" class="badge-soft" />
                    <div class="font-medium">{!! Str::limit($translation->reception_place, 30) !!}</div>
                </div>

                <div class="flex items-center justify-between gap-3 mt-2">
                    <x-badge :value="__('on')" class="badge-soft" />
                    <div>{!! Str::limit($announcement->reception_at?->locale(app()->getLocale())->translatedFormat('D, d F Y, H:i'), 30) !!}</div>
                </div>

                @if ($this->supportsImages())
                    <x-slot:figure>

                        @if ($announcement->firstPhoto())
                            <img src="{{ $announcement->firstPhoto() }}" class="w-[500px] h-[200px] object-contain" />
                        @else
                            <x-placeholder.image class="w-[500px] h-[200px]" />
                        @endif

                    </x-slot:figure>
                @endif

                @can('update', $announcement)
                    <x-slot:menu>
                        <x-button icon="o-pencil" class="btn-circle btn-sm" :tooltip="__('Edit')"
                            link="{{ $this->supportsImages()
                                ? route('senders.edit', ['announcement' => $announcement])
                                : route('couriers.edit', ['announcement' => $announcement]) }}" />
                        <x-button icon="o-trash" class="cursor-pointer" :tooltip="__('Delete')"
                            wire:click="delete({{ $announcement->id }})"
                            wire:confirm="{{ __('Are you sure you want to delete your ad?') }}" spinner="delete" />
                    </x-slot:menu>
                @endcan

                <x-slot:actions separator>
                    <x-button :label="__('Details')" class="btn-primary"
                        link="{{ $this->supportsImages()
                            ? route('senders.show', ['announcement' => $announcement])
                            : route('couriers.show', ['announcement' => $announcement]) }}" />
                </x-slot:actions>
            </x-card>
        @endforeach
    </div>

    {{ $announcements->onEachSide(0)->links('vendor.livewire.postman-pagination' /*, ['scrollTo' => false]*/) }}

    <x-drawer wire:model="drawer" :title="__('Filters')" :subtitle="__('Narrow your search results.')" separator with-close-button close-on-escape
        class="w-11/12 lg:w-1/3" right>
        <div>
            <x-form wire:submit="save">
                <x-input label="{{ __('By thing') }}" wire:model.live="thing" placeholder="{{ __('A thing') }}"
                    icon="o-question-mark-circle" clearable />

                <x-hr target="thing" />

                <x-textarea label="{{ __('By item description') }}" wire:model.live="description"
                    placeholder="{{ __('Item description') }}" rows="5" />

                <x-hr target="description" />

                <x-dimensions-weight-filters label="{{ __('By dimensions and weight') }}"
                    class="grid-cols-2 gap-x-5" />

                <x-input label="{{ __('By posting place') }}" wire:model.live="postingPlace"
                    placeholder="{{ __('Posting place') }}" clearable />
                <x-hr target="postingPlace" />

                <x-input label="{{ __('By reception place') }}" wire:model.live="receptionPlace"
                    placeholder="{{ __('Reception place') }}" clearable />
                <x-hr target="receptionPlace" />

                <div class="max-sm:space-y-6 sm:grid grid-cols-2 gap-x-5">

                    <x-datetime label="{{ __('Posting Date + Time') }}" wire:model.live="posting_at"
                        type="datetime-local" />

                    <x-datetime label="{{ __('Reception Date + Time') }}" wire:model.live="reception_at"
                        type="datetime-local" />

                </div>
            </x-form>
        </div>

        <x-slot:actions>
            <x-button :label="__('Cancel All')" @click="$wire.drawer = false" wire:click="removeFilters" />
        </x-slot:actions>
    </x-drawer>
</div>
