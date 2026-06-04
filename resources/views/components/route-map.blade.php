<div class="mt-4 rounded-box bg-base-200 p-4">
    <div class="font-semibold">
        {{ __('Route map') }}
    </div>

    <div class="text-sm opacity-70">
        {{ $from }} → {{ $to }}
    </div>

    <div wire:ignore class="mt-4">
        <div x-data x-init="const map = L.map($refs.map).setView([51.0, 15.0], 5);
        
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);">
            <div x-ref="map" class="h-96 rounded-box"></div>
        </div>
    </div>
</div>
