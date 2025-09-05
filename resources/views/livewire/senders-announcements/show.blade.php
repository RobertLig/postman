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

    public $description;

    public $metricOrImperial;

    public $dimensionLength;

    public $width;

    public $height;

    public $weight;

    public $kg;

    public $cm;

    public string $postingPlace;

    public string $receptionPlace;

    public $postingDay;

    public $receptionDay;

    public $postingMonth;

    public $receptionMonth;

    public $postingYear; 

    public $receptionYear;

    public $postingHour;

    public $receptionHour;

    public $postingMinute;

    public $receptionMinute;

    public function mount(SenderAnnouncement $senderannouncement): void //received from route parameter
    {
        //dd($senderannouncement); //route model minding works!
        $this->senderannouncement = $senderannouncement;

        $this->language = Language::where('code', App::currentLocale())->first();

        $this->thing = $this->senderannouncement->translate($this->language->id)->thing;

        $this->description = $this->senderannouncement->translate($this->language->id)->description;

        $this->metricOrImperial = 'metric'; //metric | imperial

        $this->dimensionLength = $this->senderannouncement->getDimension($this->metricOrImperial)->length;

        $this->width = $this->senderannouncement->getDimension($this->metricOrImperial)->width;

        $this->height = $this->senderannouncement->getDimension($this->metricOrImperial)->height;

        $this->weight = $this->senderannouncement->getWeight($this->metricOrImperial)->weight;

        $this->kg = 'kg'; //kg

        $this->cm = 'cm'; //cm

        $this->postingPlace = $this->senderannouncement->translate($this->language->id)->posting_place;

        $this->receptionPlace = $this->senderannouncement->translate($this->language->id)->reception_place;

        $this->postingDay = $this->senderannouncement->posting_day;

        $this->postingMonth = $this->senderannouncement->translate($this->language->id)->posting_month;

        $this->postingYear = $this->senderannouncement->posting_year;

        $this->postingHour = $this->senderannouncement->posting_hour;

        $this->postingMinute = $this->senderannouncement->posting_minute; 

        $this->receptionDay = $this->senderannouncement->reception_day;

        $this->receptionMonth = $this->senderannouncement->translate($this->language->id)->reception_month;

        $this->receptionYear = $this->senderannouncement->reception_year;

        $this->receptionHour = $this->senderannouncement->reception_hour;

        $this->receptionMinute = $this->senderannouncement->reception_minute;
    }

    public function updatedMetricOrImperial()
    {
        //dd($this->metricOrImperial);

        if($this->metricOrImperial == 'imperial')
        {
            $this->dimensionLength = $this->senderannouncement->getDimension('imperial')->length;

            $this->width = $this->senderannouncement->getDimension('imperial')->width;

            $this->height = $this->senderannouncement->getDimension('imperial')->height;

            $this->weight = $this->senderannouncement->getWeight('imperial')->weight;

            $this->kg = __('lbs'); //lbs

            $this->cm = __('inch'); //inch
        }
        else
        {
            $this->dimensionLength = $this->senderannouncement->getDimension('metric')->length;

            $this->width = $this->senderannouncement->getDimension('metric')->width;

            $this->height = $this->senderannouncement->getDimension('metric')->height;

            $this->weight = $this->senderannouncement->getWeight('metric')->weight;

            $this->kg = 'kg'; //kg

            $this->cm = 'cm'; //cm
        }
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

    <div class="my-5 ">{{ $description }}</div> {{-- text-base-content/80 --}}

    @php
        $metricOrImperial = [
            ['id' => 'metric' , 'name' => 'cm/kg' ],
            ['id' => 'imperial' , 'name' =>  __('inch/lbs') ],
        ];
    @endphp

    @if($weight || $dimensionLength || $width || $height)
       <x-radio label="{{ __('Metric or imperial') }}" wire:model.live="metricOrImperial" :options="$metricOrImperial" inline  /> {{-- wire:click="changeSuffix()" --}}
    @endif

    <x-hr target="metricOrImperial" />

    @if($weight || $dimensionLength || $width || $height)
    <div class="grid sm:grid-flow-col gap-x-1 w-fit ">
        @if($weight)
        <div class="mb-3 sm:mb-0 sm:me-2 ">{{ $weight }} <span>{{ $kg }}</span>,</div>
        @endif

        @if($dimensionLength)
        <div class="">{{ $dimensionLength }} <span>{{ $cm }}</span></div> 
        @endif

        @if($dimensionLength && $width)
        <div class="text-center">x</div>
        @endif

        @if($width)
        <div class="">{{ $width }} <span>{{ $cm }}</span></div>
        @endif

        @if(($dimensionLength || $width) && $height)
        <div class="text-center">x</div>
        @endif

        @if($height)
        <div class="">{{ $height }} <span>{{ $cm }}</span></div>
        @endif
    </div>
    @endif

    <div class="mt-10 grid sm:grid-cols-2 gap-3 bg-base-200 p-2 rounded-lg">
        <div class="flex  gap-3 ">
            <x-badge :value="__('From')" class="badge-soft" />
            <div class="wrap-normal">{{ $postingPlace }}</div>
        </div>

        <div class="flex  gap-3 ">
            <x-badge :value="__('on')" class="badge-soft" />
            <div>{{ $postingDay.' '.$postingMonth.' '.$postingYear.' '.$postingHour.':'.($postingMinute < 10 ? '0'.$postingMinute : $postingMinute) }}</div>
        </div>
    </div>

    <div class="mt-10 grid sm:grid-cols-2 gap-3 bg-base-200 p-2 rounded-lg">
        <div class="flex  gap-3 ">
            <x-badge :value="__('To')" class="badge-soft" />
            <div class="wrap-normal">{{ $receptionPlace }}</div>
        </div>

        <div class="flex  gap-3 ">
            <x-badge :value="__('on')" class="badge-soft" />
            <div>{{ $receptionDay.' '.$receptionMonth.' '.$receptionYear.' '.$receptionHour.':'.($receptionMinute < 10 ? '0'.$receptionMinute : $receptionMinute) }}</div>
        </div>
    </div>
</div>
