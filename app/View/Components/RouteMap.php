<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RouteMap extends Component
{
    public function __construct(
        public ?string $from = null,
        public ?string $to = null,
        public ?float $postingLatitude = null,
        public ?float $postingLongitude = null,
        public ?float $receptionLatitude = null,
        public ?float $receptionLongitude = null,
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.route-map');
    }
}
