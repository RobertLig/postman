<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RouteMap extends Component
{
    public function __construct(
        public string $from,
        public string $to,
        public string $mode = 'show',
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.route-map');
    }
}
