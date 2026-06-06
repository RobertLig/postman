<div>

    <x-header
        title="{{ $this->supportsImages() ? __('📦 Create senders` announcement') : __('🚚 Create couriers` announcement') }}"
        subtitle="{{ $this->supportsImages()
            ? __('If you would like to send something, please fill out the form and post an ad.')
            : __('If you would like to deliver something to someone, please fill out the form and post an ad.') }}"
        separator />

    <x-form wire:submit="save">
        <x-input label="{{ __('A thing') }}" wire:model="itemName" placeholder="{{ __('A thing') }}"
            icon="o-question-mark-circle" clearable />

        <x-hr target="itemName" />

        @if ($this->supportsImages())
            <livewire:sortable-image-library :model="$announcement" />
        @endif

        <x-textarea label="{{ __('Item description') }}" wire:model="description"
            placeholder="{{ __('Item description') }}" hint="{{ __('Max 1000 chars') }}" rows="5" />

        <x-hr target="description" />

        <x-dimensions-weight label="{{ __('Dimensions and weight') }}"
            class="sm:grid-cols-3 sm:gap-x-5 md:grid-cols-4" />

        <x-place-autocomplete />

        <div
            wire:key="route-map-{{ $postingLatitude }}-{{ $postingLongitude }}-{{ $receptionLatitude }}-{{ $receptionLongitude }}-{{ $postingPlace }}-{{ $receptionPlace }}">
            <x-route-map :from="$postingPlace" :to="$receptionPlace" :posting-latitude="$postingLatitude" :posting-longitude="$postingLongitude" :reception-latitude="$receptionLatitude"
                :reception-longitude="$receptionLongitude" />
        </div>

        <div class="max-sm:space-y-6 sm:grid grid-cols-2 gap-x-5">
            <x-datetime label="{{ __('Posting Date + Time') }}" wire:model="posting_at" type="datetime-local" />

            <x-datetime label="{{ __('Reception Date + Time') }}" wire:model="reception_at" type="datetime-local" />
        </div>

        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit"
                spinner="save" />
        </x-slot:actions>

    </x-form>
</div>
