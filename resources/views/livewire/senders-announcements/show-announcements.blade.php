<div>
    <x-header title="{{ $this->supportsImages() ? __('Senders` announcements') : __('Couriers` announcements') }}"
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
        'mb-5' =>
            $thing ||
            $description ||
            $metricOrImperial ||
            $dimensionLength ||
            $width ||
            $height ||
            $weight ||
            $postingPlace ||
            $receptionPlace ||
            $postingMonth ||
            $postingDay ||
            $postingYear ||
            $postingHour ||
            $postingMinute ||
            $receptionMonth ||
            $receptionDay ||
            $receptionYear ||
            $receptionHour ||
            $receptionMinute,
    ])>

        @if ($thing)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('thing') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer" x-on:click="$wire.set('thing', '')" />
            </div>
        @endif

        @if ($description)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('description') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer" x-on:click="$wire.set('description', '')" />
            </div>
        @endif

        @if ($metricOrImperial)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('metric or imperial') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('metricOrImperial', '')" />
            </div>
        @endif

        @if ($dimensionLength)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('length') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('dimensionLength', '')" />
            </div>
        @endif

        @if ($width)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('width') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer" x-on:click="$wire.set('width', '')" />
            </div>
        @endif

        @if ($height)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('height') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer" x-on:click="$wire.set('height', '')" />
            </div>
        @endif

        @if ($weight)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('weight') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer" x-on:click="$wire.set('weight', '')" />
            </div>
        @endif

        @if ($postingPlace)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('posting place') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('postingPlace', '')" />
            </div>
        @endif

        @if ($receptionPlace)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('reception place') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('receptionPlace', '')" />
            </div>
        @endif

        @if ($postingMonth)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('posting month') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('postingMonth', '')" />
            </div>
        @endif

        @if ($postingDay)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('posting day') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer" x-on:click="$wire.set('postingDay', '')" />
            </div>
        @endif

        @if ($postingYear)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('posting year') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer" x-on:click="$wire.set('postingYear', '')" />
            </div>
        @endif

        @if ($postingHour)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('posting hour') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer" x-on:click="$wire.set('postingHour', '')" />
            </div>
        @endif

        @if ($postingMinute)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('posting minute') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('postingMinute', '')" />
            </div>
        @endif

        @if ($receptionMonth)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('reception month') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('receptionMonth', '')" />
            </div>
        @endif

        @if ($receptionDay)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('reception day') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('receptionDay', '')" />
            </div>
        @endif

        @if ($receptionYear)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('reception year') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('receptionYear', '')" />
            </div>
        @endif

        @if ($receptionHour)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('reception hour') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('receptionHour', '')" />
            </div>
        @endif

        @if ($receptionMinute)
            <div class="p-1 w-fit bg-secondary text-secondary-content text-sm flex items-center rounded-xl">
                {{ __('reception minute') }}

                <x-icon name="o-x-mark" class="w-3 h-3 ms-1 cursor-pointer"
                    x-on:click="$wire.set('receptionMinute', '')" />
            </div>
        @endif

        @if (
            $thing ||
                $description ||
                $metricOrImperial ||
                $dimensionLength ||
                $width ||
                $height ||
                $weight ||
                $postingPlace ||
                $receptionPlace ||
                $postingMonth ||
                $postingDay ||
                $postingYear ||
                $postingHour ||
                $postingMinute ||
                $receptionMonth ||
                $receptionDay ||
                $receptionYear ||
                $receptionHour ||
                $receptionMinute)
            <x-button icon-right="o-x-mark" class="w-full btn-sm btn-secondary rounded-xl" :label="__('Cancel All')"
                wire:click="removeFilters" responsive />
        @endif
    </div>

    <div class="grid sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5">
        @foreach ($announcements as $announcement)
            {{-- dd($announcement->id) --}}
            <x-card :title="$announcement->translate($language->id)->thing" shadow separator progress-indicator="delete({{ $announcement->id }})"
                :key="$announcement->id">
                <div class="flex items-center justify-between gap-3">
                    <x-badge :value="__('From')" class="badge-soft" />
                    <div class="font-medium">{!! Str::limit($announcement->translate($language->id)->posting_place, 30) !!}</div>
                </div>

                <div class="flex items-center justify-between gap-3 mt-2">
                    <x-badge :value="__('on')" class="badge-soft" />
                    <div>{!! Str::limit(
                        $announcement->posting_day .
                            ' ' .
                            $announcement->translate($language->id)->posting_month .
                            ' ' .
                            $announcement->posting_year .
                            ' ' .
                            $announcement->posting_hour .
                            ':' .
                            ($announcement->posting_minute < 10 ? '0' . $announcement->posting_minute : $announcement->posting_minute),
                        30,
                    ) !!}</div>
                </div>

                <div class="flex items-center justify-between gap-3 mt-2">
                    <x-badge :value="__('To')" class="badge-soft" />
                    <div class="font-medium">{!! Str::limit($announcement->translate($language->id)->reception_place, 30) !!}</div>
                </div>

                <div class="flex items-center justify-between gap-3 mt-2">
                    <x-badge :value="__('on')" class="badge-soft" />
                    <div>{!! Str::limit(
                        $announcement->reception_day .
                            ' ' .
                            $announcement->translate($language->id)->reception_month .
                            ' ' .
                            $announcement->reception_year .
                            ' ' .
                            $announcement->reception_hour .
                            ':' .
                            ($announcement->reception_minute < 10
                                ? '0' . $announcement->reception_minute
                                : $announcement->reception_minute),
                        30,
                    ) !!}</div>
                </div>

                @if ($this->supportsImages())
                    <x-slot:figure>
                        <img src="{{ $announcement->library !== null && $announcement->library->first() ? $announcement->library->first()['url'] : Storage::url('senders-announcements/no-photo.jpg') }}"
                            class="w-[500px] h-[200px] object-contain" /> {{-- object-cover |  https://picsum.photos/500/200 --}}
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
    {{-- $announcements->onEachSide(2)->links('vendor.livewire.postman-pagination', ['scrollTo' => false]) --}}

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

                <x-create-resource-section label="{{ __('By posting date and hour') }}"
                    class="grid grid-cols-2 gap-x-5">

                    <x-carousela class="w-25" :data-carousel="$dataMonth" input="{{ $currentMonth }}" total-value="11"
                        start-value="0" model-name="postingMonth" is-live="true" prefix-zero="false"
                        :text-values="$textValuesMonth" carousel-width="col-span-2">

                        <x-slot:input-element>
                            <x-input label="{{ __('Month') }}" wire:model.live="postingMonth"
                                placeholder="{{ __('Month') }}" clearable />
                        </x-slot:input-element>

                        <x-slot:progress>
                            <x-hr target="postingMonth" />
                        </x-slot:progress>
                    </x-carousela>

                    <x-carousela class="" :data-carousel="$dataDay" input="{{ $currentDay }}"
                        total-value="{{ $calDaysInMonth }}" start-value="1" model-name="postingDay" is-live="true"
                        prefix-zero="false" :text-values="$textValuesDay">

                        <x-slot:input-element class="w-20">
                            <x-input label="{{ __('Day') }}" wire:model.live="postingDay"
                                placeholder="{{ __('Day') }}" clearable />
                        </x-slot:input-element>

                        <x-slot:progress>
                            <x-hr target="postingDay" />
                        </x-slot:progress>
                    </x-carousela>

                    <x-carousela class="" :data-carousel="$dataYear" input="{{ $currentYear }}"
                        total-value="{{ $currentYear + 17 }}" start-value="{{ $currentYear - 1 }}"
                        model-name="postingYear" is-live="true" prefix-zero="false" :text-values="$textValuesYear">

                        <x-slot:input-element>
                            <x-input label="{{ __('Year') }}" wire:model.live="postingYear"
                                placeholder="{{ __('Year') }}" clearable />
                        </x-slot:input-element>

                        <x-slot:progress>
                            <x-hr target="postingYear" />
                        </x-slot:progress>
                    </x-carousela>

                    <x-carousela class="" :data-carousel="$dataHour" input="{{ $currentHour }}" total-value="23"
                        start-value="0" model-name="postingHour" is-live="true" prefix-zero="false"
                        :text-values="$textValuesHour">

                        <x-slot:input-element>
                            <x-input label="{{ __('Hour') }}" wire:model.live="postingHour"
                                placeholder="{{ __('Hour') }}" clearable />
                        </x-slot:input-element>

                        <x-slot:progress>
                            <x-hr target="postingHour" />
                        </x-slot:progress>
                    </x-carousela>

                    <x-carousela class="" :data-carousel="$dataMinute" input="{{ $currentMinute }}" total-value="59"
                        start-value="0" model-name="postingMinute" is-live="true" prefix-zero="true"
                        :text-values="$textValuesMinute">

                        <x-slot:input-element>
                            <x-input label="{{ __('Minute') }}" wire:model.live="postingMinute"
                                placeholder="{{ __('Minute') }}" clearable />
                        </x-slot:input-element>

                        <x-slot:progress>
                            <x-hr target="postingMinute" />
                        </x-slot:progress>
                    </x-carousela>

                </x-create-resource-section>

                <x-create-resource-section label="{{ __('By reception date and hour') }}"
                    class="grid grid-cols-2 gap-x-5">

                    <x-carousela class="w-25" :data-carousel="$dataMonth" input="{{ $currentMonth }}" total-value="11"
                        start-value="0" model-name="receptionMonth" is-live="true" prefix-zero="false"
                        :text-values="$textValuesMonth" carousel-width="col-span-2">

                        <x-slot:input-element>
                            <x-input label="{{ __('Month') }}" wire:model.live="receptionMonth"
                                placeholder="{{ __('Month') }}" clearable />
                        </x-slot:input-element>

                        <x-slot:progress>
                            <x-hr target="receptionMonth" />
                        </x-slot:progress>
                    </x-carousela>

                    <x-carousela class="" :data-carousel="$dataDay" input="{{ $currentDay }}"
                        total-value="{{ $calDaysInMonth }}" start-value="1" model-name="receptionDay"
                        is-live="true" prefix-zero="false" :text-values="$textValuesDay">

                        <x-slot:input-element>
                            <x-input label="{{ __('Day') }}" wire:model.live="receptionDay"
                                placeholder="{{ __('Day') }}" clearable />
                        </x-slot:input-element>

                        <x-slot:progress>
                            <x-hr target="receptionDay" />
                        </x-slot:progress>
                    </x-carousela>

                    <x-carousela class="" :data-carousel="$dataYear" input="{{ $currentYear }}"
                        total-value="{{ $currentYear + 17 }}" start-value="{{ $currentYear - 1 }}"
                        model-name="receptionYear" is-live="true" prefix-zero="false" :text-values="$textValuesYear">

                        <x-slot:input-element>
                            <x-input label="{{ __('Year') }}" wire:model.live="receptionYear"
                                placeholder="{{ __('Year') }}" clearable />
                        </x-slot:input-element>

                        <x-slot:progress>
                            <x-hr target="receptionYear" />
                        </x-slot:progress>
                    </x-carousela>

                    <x-carousela class="" :data-carousel="$dataHour" input="{{ $currentHour }}" total-value="23"
                        start-value="0" model-name="receptionHour" is-live="true" prefix-zero="false"
                        :text-values="$textValuesHour">

                        <x-slot:input-element>
                            <x-input label="{{ __('Hour') }}" wire:model.live="receptionHour"
                                placeholder="{{ __('Hour') }}" clearable />
                        </x-slot:input-element>

                        <x-slot:progress>
                            <x-hr target="receptionHour" />
                        </x-slot:progress>
                    </x-carousela>

                    <x-carousela class="" :data-carousel="$dataMinute" input="{{ $currentMinute }}" total-value="59"
                        start-value="0" model-name="receptionMinute" is-live="true" prefix-zero="true"
                        :text-values="$textValuesMinute">

                        <x-slot:input-element>
                            <x-input label="{{ __('Minute') }}" wire:model.live="receptionMinute"
                                placeholder="{{ __('Minute') }}" clearable />
                        </x-slot:input-element>

                        <x-slot:progress>
                            <x-hr target="receptionMinute" />
                        </x-slot:progress>
                    </x-carousela>

                </x-create-resource-section>
            </x-form>
        </div>

        <x-slot:actions>
            <x-button :label="__('Cancel All')" @click="$wire.drawer = false" wire:click="removeFilters" />
            {{-- <x-button :label="__('Search...')" class="btn-primary" icon="o-check" /> --}}
        </x-slot:actions>
    </x-drawer>
</div>
