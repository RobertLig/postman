<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Courier;

class CourierAdvertisers extends Component
{
    public int $courierAnnouncementsCount;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->courierAnnouncementsCount = Courier::all()->count(); //uncomment after creating CourierAnnouncement model

        //$this->courierAnnouncementsCount = 0; //comment out after creating CourierAnnouncement model
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            @if ($courierAnnouncementsCount)
                <div {{ $attributes->class(['leading-5 ps-3']) }}> 
                    +<span>{{ $courierAnnouncementsCount }}</span> {{ trans_choice('translations.advertisers', $courierAnnouncementsCount) }}
                </div>
            @endif
        blade;
    }
}
