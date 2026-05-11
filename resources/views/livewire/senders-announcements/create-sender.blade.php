<div>

    <x-header title="{{ __('Fill out the form') }}" separator /> {{-- Create senders` announcement subtitle="{{ __('If you would like to send something, please fill out the form and post an ad.') }}" --}}

    <x-form wire:submit="save">
        <x-input label="{{ __('A thing') }}" wire:model="itemName" placeholder="{{ __('A thing') }}"
            icon="o-question-mark-circle" clearable />

        <x-hr target="itemName" />

        @if ($this->supportsImages())
            <livewire:sortable-image-library :model="$sender" />
        @endif

        <x-textarea label="{{ __('Item description') }}" wire:model="description"
            placeholder="{{ __('Item description') }}" hint="{{ __('Max 200 chars') }}" rows="5" />

        <x-hr target="description" />

        <x-dimensions-weight label="{{ __('Dimensions and weight') }}"
            class="sm:grid-cols-3 sm:gap-x-5 md:grid-cols-4" />

        <x-place-autocomplete />

        <x-create-resource-section label="{{ __('Posting date and hour') }}"
            class="sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 max-w-3xl">

            <x-carousela class="" :data-carousel="$dataDay" input="{{ $currentDay }}"
                total-value="{{ $calDaysInMonth }}" start-value="1" model-name="postingDay" is-live="false"
                prefix-zero="false" :text-values="$textValuesDay">

                <x-slot:input-element>
                    <x-input label="{{ __('Day') }}" wire:model="postingDay" placeholder="{{ __('Day') }}"
                        clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingDay" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="w-25" :data-carousel="$dataMonth" input="{{ $currentMonth }}" total-value="11" start-value="0"
                model-name="postingMonth" is-live="false" prefix-zero="false" :text-values="$textValuesMonth">

                <x-slot:input-element>
                    <x-input label="{{ __('Month') }}" wire:model="postingMonth" placeholder="{{ __('Month') }}"
                        clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingMonth" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataYear" input="{{ $currentYear }}"
                total-value="{{ $currentYear + 17 }}" start-value="{{ $currentYear - 1 }}" model-name="postingYear"
                is-live="false" prefix-zero="false" :text-values="$textValuesYear">

                <x-slot:input-element>
                    <x-input label="{{ __('Year') }}" wire:model="postingYear" placeholder="{{ __('Year') }}"
                        clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingYear" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataHour" input="{{ $currentHour }}" total-value="23" start-value="0"
                model-name="postingHour" is-live="false" prefix-zero="false" :text-values="$textValuesHour">

                <x-slot:input-element>
                    <x-input label="{{ __('Hour') }}" wire:model="postingHour" placeholder="{{ __('Hour') }}"
                        clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingHour" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataMinute" input="{{ $currentMinute }}" total-value="59" start-value="0"
                model-name="postingMinute" is-live="false" prefix-zero="true" :text-values="$textValuesMinute">

                <x-slot:input-element>
                    <x-input label="{{ __('Minute') }}" wire:model="postingMinute" placeholder="{{ __('Minute') }}"
                        clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingMinute" />
                </x-slot:progress>
            </x-carousela>

        </x-create-resource-section>

        <x-create-resource-section label="{{ __('Reception date and hour') }}"
            class="sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 max-w-3xl">

            <x-carousela class="" :data-carousel="$dataDay" input="{{ $currentDay }}"
                total-value="{{ $calDaysInMonth }}" start-value="1" model-name="receptionDay" is-live="false"
                prefix-zero="false" :text-values="$textValuesDay">

                <x-slot:input-element>
                    <x-input label="{{ __('Day') }}" wire:model="receptionDay" placeholder="{{ __('Day') }}"
                        clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionDay" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="w-25" :data-carousel="$dataMonth" input="{{ $currentMonth }}" total-value="11" start-value="0"
                model-name="receptionMonth" is-live="false" prefix-zero="false" :text-values="$textValuesMonth">

                <x-slot:input-element>
                    <x-input label="{{ __('Month') }}" wire:model="receptionMonth" placeholder="{{ __('Month') }}"
                        clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionMonth" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataYear" input="{{ $currentYear }}"
                total-value="{{ $currentYear + 17 }}" start-value="{{ $currentYear - 1 }}" model-name="receptionYear"
                is-live="false" prefix-zero="false" :text-values="$textValuesYear">

                <x-slot:input-element>
                    <x-input label="{{ __('Year') }}" wire:model="receptionYear"
                        placeholder="{{ __('Year') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionYear" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataHour" input="{{ $currentHour }}" total-value="23"
                start-value="0" model-name="receptionHour" is-live="false" prefix-zero="false" :text-values="$textValuesHour">

                <x-slot:input-element>
                    <x-input label="{{ __('Hour') }}" wire:model="receptionHour"
                        placeholder="{{ __('Hour') }}" clearable />
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionHour" />
                </x-slot:progress>
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataMinute" input="{{ $currentMinute }}" total-value="59"
                start-value="0" model-name="receptionMinute" is-live="false" prefix-zero="true" :text-values="$textValuesMinute">

                <x-slot:input-element>
                    <x-input label="{{ __('Minute') }}" wire:model="receptionMinute"
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
