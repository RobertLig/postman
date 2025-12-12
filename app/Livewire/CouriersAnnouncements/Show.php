<?php

namespace App\Livewire\CouriersAnnouncements;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Courier;
use App\Models\Language;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

#[Title('Couriers` announcement')]
class Show extends Component
{
    public Courier $courier;

    public $language;

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

    public string $metaDescription;

    public function mount(Courier $courier): void //route model binding
    {
        $this->courier = $courier;

        $this->metaDescription = $this->courier->meta_description;

        $this->language = Language::where('code', App::currentLocale())->first();

        $this->thing = $this->courier->translate($this->language->id)->thing;

        $this->description = $this->courier->translate($this->language->id)->description;

        $this->metricOrImperial = 'metric'; //metric | imperial

        $this->dimensionLength = $this->courier->getDimension($this->metricOrImperial)->length;

        $this->width = $this->courier->getDimension($this->metricOrImperial)->width;

        $this->height = $this->courier->getDimension($this->metricOrImperial)->height;

        $this->weight = $this->courier->getWeight($this->metricOrImperial)->weight;

        $this->kg = 'kg'; //kg

        $this->cm = 'cm'; //cm

        $this->postingPlace = $this->courier->translate($this->language->id)->posting_place;

        $this->receptionPlace = $this->courier->translate($this->language->id)->reception_place;

        $this->postingDay = $this->courier->posting_day;

        $this->postingMonth = $this->courier->translate($this->language->id)->posting_month;

        $this->postingYear = $this->courier->posting_year;

        $this->postingHour = $this->courier->posting_hour;

        $this->postingMinute = $this->courier->posting_minute; 

        $this->receptionDay = $this->courier->reception_day;

        $this->receptionMonth = $this->courier->translate($this->language->id)->reception_month;

        $this->receptionYear = $this->courier->reception_year;

        $this->receptionHour = $this->courier->reception_hour;

        $this->receptionMinute = $this->courier->reception_minute;
    }

    public function updatedMetricOrImperial()
    {
        //dd($this->metricOrImperial);

        if($this->metricOrImperial == 'imperial')
        {
            $this->dimensionLength = $this->courier->getDimension('imperial')->length;

            $this->width = $this->courier->getDimension('imperial')->width;

            $this->height = $this->courier->getDimension('imperial')->height;

            $this->weight = $this->courier->getWeight('imperial')->weight;

            $this->kg = __('lbs'); //lbs

            $this->cm = __('inch'); //inch
        }
        else
        {
            $this->dimensionLength = $this->courier->getDimension('metric')->length;

            $this->width = $this->courier->getDimension('metric')->width;

            $this->height = $this->courier->getDimension('metric')->height;

            $this->weight = $this->courier->getWeight('metric')->weight;

            $this->kg = 'kg'; //kg

            $this->cm = 'cm'; //cm
        }
    }

    public function getListeners()
    {
        return [
            "echo-presence:courier.{$this->courier->id},here" => 'here',
            "echo-presence:courier.{$this->courier->id},joining" => 'joining',
            "echo-presence:courier.{$this->courier->id},leaving" => 'leaving'
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

    public function render()
    {
        return view('livewire.couriers-announcements.show');
    }
}
