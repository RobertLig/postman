<?php

namespace App\Livewire\Announcement;

use Livewire\Component;
use Livewire\Attributes\On;

class MeasureSuffix extends Component
{
    public $measure;

    public function mount(): void
    {
        $this->measure = 'cm';
    }

    #[On('metric-or-imperial')]
    public function setMeasureSuffix($metricOrImperial)
    {
        //$measure = "";

        if($metricOrImperial === 'metric')
        {
            $this->measure = 'cm';
        }
        elseif($metricOrImperial === 'imperial')
        {
            $this->measure = __('inch');
        }
    }

    public function render()
    {
        return view('livewire.announcement.measure-suffix');
    }
}
