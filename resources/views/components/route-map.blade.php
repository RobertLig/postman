<div class="mt-4 rounded-box bg-base-200 p-4">
    <div class="font-semibold">
        {{ __('Route map') }}
    </div>

    <div class="text-sm opacity-70">
        {{ $from }} → {{ $to }}
    </div>

    <div wire:ignore class="mt-4">
        <div x-data x-init="const map = L.map($refs.map).setView([51, 15], 5);
        
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        
        async function geocode(place) {
            const response = await fetch(
                `/place-geocode?q=${encodeURIComponent(place)}`
            );
        
            return await response.json();
        }
        
        Promise.all([
            geocode('{{ addslashes($from) }}'),
            geocode('{{ addslashes($to) }}')
        ]).then(([fromPlace, toPlace]) => {
        
            if (!fromPlace || !toPlace) {
                return;
            }
        
            const fromMarker = L.marker([
                    fromPlace.latitude,
                    fromPlace.longitude
                ])
                .addTo(map)
                .bindPopup(fromPlace.label);
        
            const toMarker = L.marker([
                    toPlace.latitude,
                    toPlace.longitude
                ])
                .addTo(map)
                .bindPopup(toPlace.label);
        
            const bounds = L.latLngBounds([
                [fromPlace.latitude, fromPlace.longitude],
                [toPlace.latitude, toPlace.longitude]
            ]);
        
            map.fitBounds(bounds, {
                padding: [50, 50]
            });
        });">
            <div x-ref="map" class="h-96 rounded-box"></div>
        </div>
    </div>
</div>
