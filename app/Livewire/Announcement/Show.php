<?php

namespace App\Livewire\Announcement;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Sender;
use App\Models\Courier;
use App\Models\Language;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

#[Title('Couriers` announcement')]
class Show extends Component
{
    public string $type = 'sender';

    public $announcement = null; //Courier

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

    public function mount(
        string $type = 'sender',
        $announcement = null
    ): void //route model binding | Courier 
    {
        $this->type = $type;

        $modelClass = $this->modelClass();

        $this->announcement = $modelClass::findOrFail($announcement);

        $this->metaDescription = $this->announcement->meta_description;

        $this->language = Language::where('code', App::currentLocale())->first();

        $this->thing = $this->announcement->translate($this->language->id)->thing;

        $this->description = $this->announcement->translate($this->language->id)->description;

        $this->metricOrImperial = 'metric'; //metric | imperial

        $this->dimensionLength = $this->announcement->getDimension($this->metricOrImperial)->length;

        $this->width = $this->announcement->getDimension($this->metricOrImperial)->width;

        $this->height = $this->announcement->getDimension($this->metricOrImperial)->height;

        $this->weight = $this->announcement->getWeight($this->metricOrImperial)->weight;

        $this->kg = 'kg'; //kg

        $this->cm = 'cm'; //cm

        $this->postingPlace = $this->announcement->translate($this->language->id)->posting_place;

        $this->receptionPlace = $this->announcement->translate($this->language->id)->reception_place;

        $this->postingDay = $this->announcement->posting_day;

        $this->postingMonth = $this->announcement->translate($this->language->id)->posting_month;

        $this->postingYear = $this->announcement->posting_year;

        $this->postingHour = $this->announcement->posting_hour;

        $this->postingMinute = $this->announcement->posting_minute;

        $this->receptionDay = $this->announcement->reception_day;

        $this->receptionMonth = $this->announcement->translate($this->language->id)->reception_month;

        $this->receptionYear = $this->announcement->reception_year;

        $this->receptionHour = $this->announcement->reception_hour;

        $this->receptionMinute = $this->announcement->reception_minute;
    }

    protected function modelClass(): string
    {
        return $this->type === 'sender'
            ? Sender::class
            : Courier::class;
    }

    protected function supportsImages(): bool
    {
        return $this->type === 'sender';
    }

    public function updatedMetricOrImperial()
    {

        if ($this->metricOrImperial == 'imperial') {
            $this->dimensionLength = $this->announcement->getDimension('imperial')->length;

            $this->width = $this->announcement->getDimension('imperial')->width;

            $this->height = $this->announcement->getDimension('imperial')->height;

            $this->weight = $this->announcement->getWeight('imperial')->weight;

            $this->kg = __('lbs'); //lbs

            $this->cm = __('inch'); //inch
        } else {
            $this->dimensionLength = $this->announcement->getDimension('metric')->length;

            $this->width = $this->announcement->getDimension('metric')->width;

            $this->height = $this->announcement->getDimension('metric')->height;

            $this->weight = $this->announcement->getWeight('metric')->weight;

            $this->kg = 'kg'; //kg

            $this->cm = 'cm'; //cm
        }
    }

    /* public function getListeners()
    {
        return [
            "echo-presence:announcement.{$this->announcement->id},here" => 'here',
            "echo-presence:announcement.{$this->announcement->id},joining" => 'joining',
            "echo-presence:announcement.{$this->announcement->id},leaving" => 'leaving'
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
    }*/

    public function render()
    {
        return view('livewire.announcement.show');
    }
}
