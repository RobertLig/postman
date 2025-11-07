<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use App\Models\SenderAnnouncement;
use App\Models\MonthTranslation;
use App\Models\Language;
use App\Events\UserEnterAnnouncement;
use Illuminate\Support\Facades\Log;

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
        //dd($senderannouncement); //route model binding works!
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

        //mark a presence of a new user on this page (doesn't work)
        //broadcast(new UserEnterAnnouncement($this->senderannouncement));
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

    public function getListeners()
    {
        return [
            //"echo-presence:senderAnnouncement,UserEnterAnnouncement" => 'newUsersNotification', //? //"echo-presence:senderannouncement.{sender_announcement_id},UserEnterAnnouncement"
            "echo-presence:senderAnnouncement.{$this->senderannouncement->id},here" => 'here',
            "echo-presence:senderAnnouncement.{$this->senderannouncement->id},joining" => 'joining',
            "echo-presence:senderAnnouncement.{$this->senderannouncement->id},leaving" => 'leaving'
        ];
    } 

    //#[On('echo-presence:chatroom,here')]
    public function here($users)
    {
        //Log::info('All presentUsers show: {users}', ['users' => $users]);
    }

    //#[On('echo-presence:chatroom,joining')]
    public function joining($user)
    {
        //Log::info('Joining presentUsers show: {user}', ['user' => $user]);
    }

    //#[On('echo-presence:chatroom,leaving')]
    public function leaving($user)
    {
        //Log::info('Leaving presentUsers show: {user}', ['user' => $user]);
    } 
}; ?>

<div>
    <x-header title="{{ __('Sender` announcement') }}" subtitle="{{ __('See ad details.') }}" separator />

    <div class="text-3xl font-bold">{{ $thing }}</div>

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

    <x-show-weight-length-width-height weight="{{ $weight }}" dimension-length="{{ $dimensionLength }}" width="{{ $width }}" height="{{ $height }}" kg="{{ $kg }}" cm="{{ $cm }}"/>

    <x-show-from-to-place-date-time posting-place="{{ $postingPlace }}" reception-place="{{ $receptionPlace }}" posting-day="{{ $postingDay }}" reception-day="{{ $receptionDay }}"
        posting-month="{{ $postingMonth }}" reception-month="{{ $receptionMonth }}" posting-year="{{ $postingYear }}" reception-year="{{ $receptionYear }}" posting-hour="{{ $postingHour }}" 
        reception-hour="{{ $receptionHour }}" posting-minute="{{ $postingMinute }}" reception-minute="{{ $receptionMinute }}" /> 

    <div class="divider"></div>

    @if(auth()->user())
        <div class="text-xl font-medium mt-10">{{ __('Advertiser') }}</div>

        <x-list-item :item="$senderannouncement->user" class="mt-3" > 
            <x-slot:avatar>
                <x-avatar :image="$senderannouncement->user->getAvatar()" 
                        placeholder="{{ $senderannouncement->user->initials() }}" class="!w-10" />
            </x-slot:avatar>

            <x-slot:sub-value>
                <div>{{ __($senderannouncement->user->gender) }}</div>

                @if($senderannouncement->user->age)
                    <div>{{ __($senderannouncement->user->age) }} {{ __('years') }}</div>
                @endif
            </x-slot:sub-value>

        </x-list-item>

        <div class="mb-5"></div>

        @can('talk', $senderannouncement->user) 
            <livewire:chat :user="$senderannouncement->user" :announcement="$senderannouncement" /> 
        @endcan

    @endif

</div>
