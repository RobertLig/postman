<div>
    <x-header title="{{ __('Create senders` announcement') }}"
        subtitle="{{ __('If you would like to send something, please fill out the form and post an ad.') }}" separator />

    <x-form wire:submit="save">
        <x-input label="{{ __('A thing') }}" wire.model.live="thing" placeholder="{{ __('A thing') }}"
            icon="o-question-mark-circle" clearable />

        <x-hr target="thing" />

        <livewire:sortable-image-library :sender="$sender" />

        <x-textarea label="{{ __('Item description') }}" wire:model.live="description"
            placeholder="{{ __('Item description') }}" hint="{{ __('Max 200 chars') }}" rows="5" />

        <x-hr target="description" />

        <x-dimensions-weight label="{{ __('Dimensions and weight') }}"
            class="sm:grid-cols-3 sm:gap-x-5 md:grid-cols-4" />

        <x-place-autocomplete />

        <x-create-resource-section label="{{ __('Posting date and hour') }}"
            class="sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 max-w-3xl"> {{-- sm:grid-cols-2 xl:grid-cols-3 max-w-3xl --}}

            {{-- <livewire:announcement.post-day /> component not working. Couldn't reset properties on Alpine with $wire.entangle() during livewire server roundtrip. Issue not solved --}}

            <x-carousela class="" :data-carousel="$dataDay" input="{{ $currentDay }}"
                total-value="{{ $calDaysInMonth }}" start-value="1" model-name="postingDay" is-live="true"
                prefix-zero="false" :text-values="$textValuesDay">

                <x-slot:input-element>
                    <x-input label="{{ __('Day') }}" wire:model.live="postingDay"
                        placeholder="{{ __('Day') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingDay" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="w-25" :data-carousel="$dataMonth" input="{{ $currentMonth }}" total-value="11" start-value="0"
                model="postingMonth" is-live="true" prefix-zero="false" :text-values="$textValuesMonth">

                <x-slot:input-element>
                    <x-input label="{{ __('Month') }}" wire:model.live="postingMonth"
                        placeholder="{{ __('Month') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingMonth" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataYear" input="{{ $currentYear }}"
                total-value="{{ $currentYear + 17 }}" start-value="{{ $currentYear - 1 }}" model-name="postingYear"
                is-live="true" prefix-zero="false" :text-values="$textValuesYear">

                <x-slot:input-element>
                    <x-input label="{{ __('Year') }}" wire:model.live="postingYear"
                        placeholder="{{ __('Year') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingYear" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataHour" input="{{ $currentHour }}" total-value="23" start-value="0"
                model-name="postingHour" is-live="true" prefix-zero="false" :text-values="$textValuesHour">

                <x-slot:input-element>
                    <x-input label="{{ __('Hour') }}" wire:model.live="postingHour"
                        placeholder="{{ __('Hour') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingHour" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataMinute" input="{{ $currentMinute }}" total-value="59" start-value="0"
                model-name="postingMinute" is-live="true" prefix-zero="true" :text-values="$textValuesMinute">

                <x-slot:input-element>
                    <x-input label="{{ __('Minute') }}" wire:model.live="postingMinute"
                        placeholder="{{ __('Minute') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingMinute" />
                </x-slot:progress>
            </x-carousela>

        </x-create-resource-section>

        <x-create-resource-section label="{{ __('Reception date and hour') }}"
            class="sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 max-w-3xl">

            <x-carousela class="" :data-carousel="$dataDay" input="{{ $currentDay }}"
                total-value="{{ $calDaysInMonth }}" start-value="1" model-name="receptionDay" is-live="true"
                prefix-zero="false" :text-values="$textValuesDay">

                <x-slot:input-element>
                    <x-input label="{{ __('Day') }}" wire:model.live="receptionDay"
                        placeholder="{{ __('Day') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionDay" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="w-25" :data-carousel="$dataMonth" input="{{ $currentMonth }}" total-value="11" start-value="0"
                model-name="receptionMonth" is-live="true" prefix-zero="false" :text-values="$textValuesMonth">

                <x-slot:input-element>
                    <x-input label="{{ __('Month') }}" wire:model.live="receptionMonth"
                        placeholder="{{ __('Month') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionMonth" />
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
                start-value="0" model-name="receptionHour" is-live="true" prefix-zero="false" :text-values="$textValuesHour">

                <x-slot:input-element>
                    <x-input label="{{ __('Hour') }}" wire:model.live="receptionHour"
                        placeholder="{{ __('Hour') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionHour" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataMinute" input="{{ $currentMinute }}" total-value="59"
                start-value="0" model-name="receptionMinute" is-live="true" prefix-zero="true" :text-values="$textValuesMinute">

                <x-slot:input-element>
                    <x-input label="{{ __('Minute') }}" wire:model.live="receptionMinute"
                        placeholder="{{ __('Minute') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionMinute" />
                </x-slot:progress>
            </x-carousela>

        </x-create-resource-section>

        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
