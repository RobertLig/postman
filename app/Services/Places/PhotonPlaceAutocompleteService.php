<?php

namespace App\Services\Places;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Services\Places\Contracts\PlaceAutocompleteServiceInterface;

class PhotonPlaceAutocompleteService
implements PlaceAutocompleteServiceInterface
{
    public function search(string $query): array
    {
        if (trim($query) === '') {
            return [];
        }

        \Illuminate\Support\Facades\Log::debug('app locale', [
            app()->getLocale()
        ]);

        \Illuminate\Support\Facades\Log::debug('mcamara locale', [
            \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale()
        ]);

        $locale = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();

        return Cache::remember(
            'place-autocomplete:' . $locale . ':' . md5($query),
            now()->addHours(24),
            function () use ($query, $locale) {

                $response = Http::get(
                    'https://photon.komoot.io/api/',
                    [
                        'q' => $query,
                        'lang' => $locale,
                        'limit' => 5,
                    ]
                );

                if ($response->failed()) {
                    return [];
                }

                return collect($response->json('features'))
                    ->map(function ($feature) {
                        $properties = $feature['properties'];

                        $parts = collect([
                            $properties['name'] ?? null,
                            $properties['city'] ?? null,
                            $properties['country'] ?? null,
                        ])
                            ->filter()
                            ->unique()
                            ->values()
                            ->toArray();

                        return [
                            'label' => implode(', ', $parts),

                            'latitude' => $feature['geometry']['coordinates'][1] ?? null,

                            'longitude' => $feature['geometry']['coordinates'][0] ?? null,
                        ];
                    })
                    ->unique('label')
                    ->values()
                    ->toArray();
            }
        );
    }
}
