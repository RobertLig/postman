<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Places\Contracts\PlaceAutocompleteServiceInterface;

class PlaceAutocompleteController extends Controller
{
    public function __invoke(
        Request $request,
        PlaceAutocompleteServiceInterface $placeAutocompleteService
    ) {
        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        return response()->json(
            $placeAutocompleteService->search(
                $validated['q']
            )
        );
    }
}
