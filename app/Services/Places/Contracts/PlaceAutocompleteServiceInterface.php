<?php

namespace App\Services\Places\Contracts;

interface PlaceAutocompleteServiceInterface
{
    public function search(string $query): array;
}
