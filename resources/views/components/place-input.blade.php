<div x-data="{
    property: '{{ $property }}',
    latitudeProperty: '{{ $latitudeProperty }}',
    longitudeProperty: '{{ $longitudeProperty }}',

    newestRequestId: 0,

    async makeAutocompleteRequest($event) {
        $wire.set(this.latitudeProperty, null, true);

        $wire.set(this.longitudeProperty, null, true);

        if ($event.target.value.trim().length < 2) {
            this.closeResultsContainerElement();

            return;
        }

        const requestId = ++this.newestRequestId;

        const response = await fetch(
            `{{ route('place-autocomplete') }}?q=${encodeURIComponent(
                 $event.target.value
            )}`
        );

        const suggestions = await response.json();

        if (suggestions.length === 0) {
            this.closeResultsContainerElement();

            return;
        }

        // If the request has been superseded by a newer request, do not render the output.
        if (requestId !== this.newestRequestId) {
            return;
        }

        // Clear the list first
        this.$refs.results.replaceChildren();

        this.$refs.results.classList.add('border-[length:var(--border)]');

        for (const suggestion of suggestions) {
            const li = document.createElement('li');

            li.classList.add(
                'p-2',
                'w-full',
                'border-b',
                'border-base-200',
                'cursor-pointer',
                'hover:bg-base-200'
            );

            li.innerText = suggestion.label;

            li.addEventListener('click', () => {
                this.onPlaceSelected(suggestion);
            });

            this.$refs.results.appendChild(li);
        }
    },

    closeResultsContainerElement() {
        this.$refs.results.replaceChildren();

        this.$refs.results.classList.remove(
            'border-[length:var(--border)]'
        );
    },

    clearPlace() {
        $wire.set(this.property, '', true);

        $wire.set(this.latitudeProperty, null, true);

        $wire.set(this.longitudeProperty, null, true);

        this.closeResultsContainerElement();
    },

    onPlaceSelected(place) {
        $wire.set(
            this.property,
            place.label,
            true
        );

        $wire.set(
            this.latitudeProperty,
            place.latitude,
            true
        );

        $wire.set(
            this.longitudeProperty,
            place.longitude,
            true
        );

        this.closeResultsContainerElement();
    }
}">
    <div class="relative">
        <x-map-input :label="$label" :wire:model="$property" placeholder="{{ $label }}" clearable
            icon="o-map-pin" @input.debounce.300ms="makeAutocompleteRequest($event)" />

        <ul wire:ignore x-ref="results"
            class="list absolute rounded-lg shadow border-base-content/10 bg-base-100 z-10 w-full"></ul>

        <x-hr :target="$property" />
    </div>
</div>
