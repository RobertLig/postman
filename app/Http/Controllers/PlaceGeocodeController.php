<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Places\Contracts\PlaceAutocompleteServiceInterface;

class PlaceGeocodeController extends Controller
{
    public function __invoke(
        Request $request,
        PlaceAutocompleteServiceInterface $service
    ) {
        $validated = $request->validate([
            'q' => ['required', 'string'],
        ]);

        $result = collect(
            $service->search($validated['q'])
        )->first();

        return response()->json($result);
    }
}
