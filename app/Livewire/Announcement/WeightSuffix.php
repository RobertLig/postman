<?php

namespace App\Livewire\Announcement;

use Livewire\Component;
use Livewire\Attributes\On;

class WeightSuffix extends Component
{
    public $weight;

    public function mount(): void
    {
        $this->weight = 'kg';
    }

    #[On('metric-or-imperial')]
    public function setWeightSuffix($metricOrImperial)
    {
        //$weight = "";

        if($metricOrImperial === 'metric')
        {
            $this->weight = 'kg';
        }
        elseif($metricOrImperial === 'imperial')
        {
            $this->weight = __('lbs');
        }
    }

    public function render()
    {
        return view('livewire.announcement.weight-suffix');
    }
}
