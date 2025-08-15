<?php
//component not working. Couldn't reset properties on Alpine with $wire.entangle() during livewire server roundtrip. Issue not solved
namespace App\Livewire\Announcement;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class PostDay extends Component
{
    #[Validate(['image'])]
    public $postingDay;

    public array $dataDay; 
    public $textValuesDay;
    public int $input; //$currentDay
    public int $totalValue; //$calDaysInMonth

    public int $currentDegree;
    public int $nodeValue;

    public function mount(): void
    {
        $this->input = date("j", mktime(0,0,0, date("n"), date("j"), date("Y")));

        $this->totalValue = cal_days_in_month(CAL_GREGORIAN, date("n"), date("Y"));

        $this->dataDay = [
            $this->input, 
            date("j", mktime(0,0,0, date("n"), date("j") + 1, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 2, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 3, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 4, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 4, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 3, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 2, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 1, date("Y")))
        ];

        $this->currentDegree = 0;

        $this->nodeValue = 0;
    }

    public function boot() 
    {
        $this->currentDegree = 0;

        $this->nodeValue = 0;
    }

    #[On('updated-posting-month-year')]
    public function updateDayCarousel($month, $year, $calDaysInMonth)//$calDaysInMonth, $monthYearCalDays
    {
        $this->totalValue = $calDaysInMonth;

        if($this->postingDay > $calDaysInMonth)
        {
            $this->input = 1;

            $this->postingDay = null;
        } 
        elseif($this->postingDay <= $calDaysInMonth && $this->postingDay != null)
        {
            $this->input = $this->postingDay;

            //dd($this->postingDay);
        }

        $this->setNodes();

        /*$this->dataDay = [
                $this->currentDay, 
                date("j", mktime(0,0,0, $month, $this->currentDay + 1, $year)), 
                date("j", mktime(0,0,0, $month, $this->currentDay + 2, $year)), 
                date("j", mktime(0,0,0, $month, $this->currentDay + 3, $year)), 
                date("j", mktime(0,0,0, $month, $this->currentDay + 4, $year)), 
                date("j", mktime(0,0,0, $month, $this->currentDay - 4, $year)), 
                date("j", mktime(0,0,0, $month, $this->currentDay - 3, $year)), 
                date("j", mktime(0,0,0, $month, $this->currentDay - 2, $year)), 
                date("j", mktime(0,0,0, $month, $this->currentDay - 1, $year))
        ]; */
        
        //dd($month, $year, $calDaysInMonth);
    }

    private function setNodes() 
    {
        $this->dataDay = [];
        $startValue = 1;

        $this->dataDay[] = $this->input;

        for($i = 1; $i <= 4; $i++)
        {
            $input = $this->input + $i;

            if($input > $this->totalValue) 
            {
                $aboveLimit = $input - $this->totalValue;

                $this->dataDay[] = $startValue + ($aboveLimit - 1);
            }
            else
            {
                $this->dataDay[] = $input;
            }
        }

        for($i = 4; $i >= 1; $i--) 
        {
            $input = $this->input - $i;

            if($input < $startValue) 
            { 
                $belowLimit = $startValue - $input;

                $this->dataDay[] = $this->totalValue - ($belowLimit - 1);
            }
            else
            {
                $this->dataDay[] = $input;
            }
        }
    }

    public function render()
    {
        return view('livewire.announcement.post-day');
    }
}
