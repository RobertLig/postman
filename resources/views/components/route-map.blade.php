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
        
        const postingLatitude = {{ $postingLatitude ?? 'null' }};
        const postingLongitude = {{ $postingLongitude ?? 'null' }};
        
        const receptionLatitude = {{ $receptionLatitude ?? 'null' }};
        const receptionLongitude = {{ $receptionLongitude ?? 'null' }};
        
        const points = [];
        
        if (postingLatitude !== null && postingLongitude !== null) {
        
            L.marker([
                    postingLatitude,
                    postingLongitude
                ])
                .addTo(map)
                .bindPopup(@js($from));
        
            points.push([
                postingLatitude,
                postingLongitude
            ]);
        }
        
        if (receptionLatitude !== null && receptionLongitude !== null) {
        
            L.marker([
                    receptionLatitude,
                    receptionLongitude
                ])
                .addTo(map)
                .bindPopup(@js($to));
        
            points.push([
                receptionLatitude,
                receptionLongitude
            ]);
        }
        
        if (points.length === 2) {
        
            L.polyline(points).addTo(map);
        
            map.fitBounds(points, {
                padding: [50, 50]
            });
        
        } else if (points.length === 1) {
        
            map.setView(points[0], 10);
        }">
            <div x-ref="map" class="h-96 rounded-box"></div>
        </div>
    </div>
</div>
