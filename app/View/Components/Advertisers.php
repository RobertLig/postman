<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\SenderAnnouncement;

class Advertisers extends Component /*AdvertisersNumber*/ 
{
    public int $senderAnnouncementsCount;

    /**
     * Create a new component instance. Just select the number of advertisers from the database and show it on the page
     */
    public function __construct()
    {
        $this->senderAnnouncementsCount = SenderAnnouncement::all()->count();

        //dd($senderAnnouncementsCount);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            @if ($senderAnnouncementsCount)
                <div {{ $attributes->class(['leading-5 ps-3']) }}> 
                    +<span>{{ $senderAnnouncementsCount }}</span> {{ trans_choice('translations.advertisers', $senderAnnouncementsCount) }}
                </div>
            @endif
        blade;
    }
}
