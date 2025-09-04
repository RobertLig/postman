<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use App\Models\SenderAnnouncement;
use App\Models\MonthTranslation;
use App\Models\Language;
use Illuminate\Support\Facades\Storage;

new #[Title('Senders` announcement')]
class extends Component {
    public SenderAnnouncement $senderannouncement;

    public $thing;

    public function mount(SenderAnnouncement $senderannouncement): void //received from route parameter
    {
        //dd($senderannouncement); //route model minding works!
        $this->senderannouncement = $senderannouncement;

        $this->language = Language::where('code', App::currentLocale())->first();

        $this->thing = $this->senderannouncement->translate($this->language->id)->thing;
    }
}; ?>

<div>
    <x-header title="{{ __('Sender` announcement') }}" subtitle="{{ __('See ad details.') }}" separator />

    <div class="text-3xl">{{ $thing }}</div>

    @php
    if($senderannouncement->library->count())
    {
        $slides = [];

        foreach($senderannouncement->library as $image)
        {
            $slides[] = ['image' => $image['url']]; //https://picsum.photos/500/200?random=1
        }
    }
    @endphp 
 
    @if($senderannouncement->library->count())
      <x-carousel :slides="$slides" class="mt-3" />
    @endif
</div>
