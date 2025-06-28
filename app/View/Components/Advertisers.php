<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Advertisers extends Component /*AdvertisersNumber*/ 
{
    /**
     * Create a new component instance. Just select the number of advertisers from the database and show it on the page
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div {{ $attributes->class([]) }}>
                +<span>23</span> {{ trans_choice('translations.advertisers', 23) }}
            </div>
        blade;
    }
}
