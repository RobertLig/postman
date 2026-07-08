<div class="mt-4 rounded-box bg-base-200 p-4">
    <div class="font-semibold">
        {{ __('Route map') }}
    </div>

    @php
        $visibleLocations = [];

        if ($postingLatitude !== null && $postingLongitude !== null) {
            $visibleLocations[] = $from;
        }

        foreach ($routeStops as $stop) {
            if ($stop['latitude'] !== null && $stop['longitude'] !== null) {
                $visibleLocations[] = $stop['label'];
            }
        }

        if ($receptionLatitude !== null && $receptionLongitude !== null) {
            $visibleLocations[] = $to;
        }
    @endphp

    @if ($visibleLocations)
        <div class="text-sm opacity-70">
            {{ implode(' → ', $visibleLocations) }}
        </div>
    @endif

    {{-- <div class="text-sm opacity-70">
        {{ $from }} → {{ $to }}
    </div> --}}

    {{-- $postingLatitude . ' ' . $postingLongitude --}}

    <div wire:ignore class="mt-4">
        <div x-data x-init="const map = L.map($refs.map).setView([51, 15], 5);
        
        //console.log('RouteMap initialized');
        
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
        
        const postingLatitude = {{ $postingLatitude ?? 'null' }};
        const postingLongitude = {{ $postingLongitude ?? 'null' }};
        
        const receptionLatitude = {{ $receptionLatitude ?? 'null' }};
        const receptionLongitude = {{ $receptionLongitude ?? 'null' }};
        
        const routeStops = @js($routeStops);
        
        const points = [];
        
        if (postingLatitude !== null && postingLongitude !== null) {
        
            points.push([
                postingLatitude,
                postingLongitude
            ]);
        
            L.marker([
                    postingLatitude,
                    postingLongitude
                ])
                .addTo(map)
                .bindPopup('📦 ' + @js($from))
        }
        
        routeStops.forEach(stop => {
        
            if (
                stop.latitude !== null &&
                stop.longitude !== null
            ) {
        
                points.push([
                    stop.latitude,
                    stop.longitude
                ]);
        
                L.marker([
                        stop.latitude,
                        stop.longitude
                    ])
                    .addTo(map)
                    .bindPopup('📍 ' + stop.label)
            }
        });
        
        if (receptionLatitude !== null && receptionLongitude !== null) {
        
            points.push([
                receptionLatitude,
                receptionLongitude
            ]);
        
            L.marker([
                    receptionLatitude,
                    receptionLongitude
                ])
                .addTo(map)
                .bindPopup('🏁 ' + @js($to))
        }
        
        if (points.length >= 2) {
        
            L.polyline(points).addTo(map);
        
            map.fitBounds(points, {
                padding: [50, 50]
            });
        
        } else if (points.length === 1) {
        
            map.setView(points[0], 10);
        }">
            {{-- <div>
                Map instance: {{ now()->timestamp }}
            </div> --}}

            <div x-ref="map" class="h-96 rounded-box"></div>
        </div>
    </div>
</div>
