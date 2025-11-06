<?php

namespace App\Livewire\CouriersAnnouncements;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Google\Cloud\Translate\V3\Client\TranslationServiceClient;
use Google\Cloud\Translate\V3\TranslateTextRequest;
use App\Models\Courier;
use App\Models\MonthTranslation;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Exception;

#[Title('Create couriers` announcement')]
class Create extends Component
{
    #[Validate('required|string|max:20')]
    public $thing;

    #[Validate('nullable|string|max:200')]
    public $description;

    #[Validate('required|string|in:metric,imperial')]
    public $metricOrImperial;

    #[Validate('nullable|integer|min:1')]
    public $dimensionLength; //can't be $length name for a property. Alpine.js doesn't accept

    #[Validate('nullable|integer|min:1')]
    public $width;

    #[Validate('nullable|integer|min:1')]
    public $height;

    #[Validate('nullable|integer|min:1')]
    public $weight;

    #[Validate('required|integer|between:1,31')]
    public $postingDay;

    #[Validate('required|integer|between:1,31')]
    public $receptionDay;

    public array $dataDay; 
    public $textValuesDay;
    public int $currentDay;
    public int $calDaysInMonth;

    #[Validate('required|string|in:January,February,March,April,May,June,July,August,September,October,November,December,styczeń,luty,marzec,kwiecień,maj,czerwiec,lipiec,sierpień,wrzesień,październik,listopad,grudzień')]
    public $postingMonth;

    #[Validate('required|string|in:January,February,March,April,May,June,July,August,September,October,November,December,styczeń,luty,marzec,kwiecień,maj,czerwiec,lipiec,sierpień,wrzesień,październik,listopad,grudzień')]
    public $receptionMonth;

    public array $dataMonth;
    public $textValuesMonth;
    public string $currentMonth;

    #[Validate('required|integer|min:2024|date_format:Y')]
    public $postingYear; 

    #[Validate('required|integer|min:2024|date_format:Y')]
    public $receptionYear;

    public array $dataYear;
    public $textValuesYear;
    public string $currentYear;

    #[Validate('required|integer|between:0,23')]
    public $postingHour;

    #[Validate('required|integer|between:0,23')]
    public $receptionHour;

    public array $dataHour;
    public $textValuesHour;
    public string $currentHour;

    #[Validate('required|integer|between:0,59')]
    public $postingMinute;

    #[Validate('required|integer|between:0,59')]
    public $receptionMinute;

    public array $dataMinute;
    public $textValuesMinute;
    public string $currentMinute;

    #[Validate('required|string|max:200')]
    public string $postingPlace;

    #[Validate('required|string|max:200|different:postingPlace')]
    public string $receptionPlace;

    public function mount(): void
    {
        $this->metricOrImperial = 'metric';

        //day
        //$this->currentDay = date("j", mktime(0,0,0, date("n"), date("j"), date("Y")));
        $this->currentDay = 1;

        //$this->calDaysInMonth = cal_days_in_month(CAL_GREGORIAN, date("n"), date("Y"));
        $this->calDaysInMonth = 31;

        //must have 9 elements for Carousela component logic. This logic may be wrong because of possibility to increment or decrement into previous or next month
        /*$this->dataDay = [
            $this->currentDay, 
            date("j", mktime(0,0,0, date("n"), date("j") + 1, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 2, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 3, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 4, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 4, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 3, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 2, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 1, date("Y")))
        ]; */

        $this->dataDay = [1, 2, 3, 4, 5, 28, 29, 30, 31];

        //month
        $this->currentMonth = date("n", mktime(0,0,0, date("n"), date("j"), date("Y"))) - 1;

        $this->dataMonth = [
            __( date("F", mktime(0,0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") + 1, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") + 2, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") + 3, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") + 4, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") - 4, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") - 3, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") - 2, date("j"), date("Y"))) ), 
            __( date("F", mktime(0,0,0, date("n") - 1, date("j"), date("Y"))) )
        ]; 

        $this->textValuesMonth = [ __('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December')];
    
        //year
        $this->currentYear = date("Y", mktime(0,0,0, date("n"), date("j"), date("Y")));

        $this->dataYear = [
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y"))), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 1)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 2)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 3)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 4)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 15)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 16)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 17)), 
            date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") - 1))
        ]; 

        //hour
        $this->currentHour = date("G", mktime(date("G"),0,0, date("n"), date("j"), date("Y")));

        $this->dataHour = [
            date("G", mktime(date("G"),0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") + 1,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") + 2,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") + 3,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") + 4,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") - 4,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") - 3,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") - 2,0,0, date("n"), date("j"), date("Y"))), 
            date("G", mktime(date("G") - 1,0,0, date("n"), date("j"), date("Y")))
        ];

        //minute
        $this->currentMinute = (int)date("i", mktime(date("G"),date("i"),0, date("n"), date("j"), date("Y")));

        $this->dataMinute = [
            date("i", mktime(date("G"),date("i"),0, date("n"), date("j"), date("Y"))) , 
            date("i", mktime(date("G"),date("i") + 1,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") + 2,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") + 3,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") + 4,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") - 4,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") - 3,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") - 2,0, date("n"), date("j"), date("Y"))), 
            date("i", mktime(date("G"),date("i") - 1,0, date("n"), date("j"), date("Y")))
        ];
    }

    public function changeSuffix()
    {
        $this->dispatch('metric-or-imperial', metricOrImperial: $this->metricOrImperial);
    }

    public function boot() 
    {   
        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {

                //dates (can't be too many days in a month or posting can't be equal or bigger than reception)
                if($this->postingDay && $this->postingMonth && $this->postingYear && $this->postingHour && $this->postingMinute &&
                   $this->receptionDay && $this->receptionMonth && $this->receptionYear && $this->receptionHour && $this->receptionMinute)
                {
                    $postingMonthTranslation = MonthTranslation::where('month', $this->postingMonth)->first(); //$postingMonthTranslation->month_id

                    //$dateTimeObj = DateTime::createFromFormat('Y-n-j', $dateTime);

                    $totalPostingDaysAllowed = cal_days_in_month(CAL_GREGORIAN, $postingMonthTranslation->month_id, $this->postingYear);

                    if($this->postingDay > $totalPostingDaysAllowed) //!($dateTimeObj && $dateTimeObj->format('Y-n-j') == $dateTime)
                    {
                        $validator->errors()->add("postingDay", __('Too many days in this month.'));

                        //dd($validator->errors()->get("postingDay"));
                    }

                    $receptionMonthTranslation = MonthTranslation::where('month', $this->receptionMonth)->first(); 

                    //$dateTimeObj = DateTime::createFromFormat('Y-n-j', $dateTime);

                    $totalReceptionDaysAllowed = cal_days_in_month(CAL_GREGORIAN, $receptionMonthTranslation->month_id, $this->receptionYear);

                    if($this->receptionDay > $totalReceptionDaysAllowed) //!($dateTimeObj && $dateTimeObj->format('Y-n-j') == $dateTime)
                    {
                        $validator->errors()->add("receptionDay", __('Too many days in this month.'));

                        //dd($validator->errors()->get("receptionDay"));
                    }


                    $origin = $this->postingYear.'-'.$postingMonthTranslation->month_id.'-'.$this->postingDay.' '.$this->postingHour.':'.$this->postingMinute;

                    $target = $this->receptionYear.'-'.$receptionMonthTranslation->month_id.'-'.$this->receptionDay.' '.$this->receptionHour.':'.$this->receptionMinute;

                    $dateTimestamp1 = strtotime($origin);
                    $dateTimestamp2 = strtotime($target);

                    if ($dateTimestamp1 >= $dateTimestamp2)
                    {
                        $validator->errors()->add("receptionMinute", __('Reception must be later than posting.'));

                        //dd('Reception must be later than posting.');
                    }
                }
            });
        });
    }

    public function save()
    {
        $this->validate();

        $user = Auth::user();

        $courier = Courier::create([
            'user_id' => $user->id,
            'posting_day' => $this->postingDay,
            'posting_year' => $this->postingYear,
            'posting_hour' => $this->postingHour,
            'posting_minute' => $this->postingMinute,
            'reception_day' => $this->receptionDay,
            'reception_year' => $this->receptionYear,
            'reception_hour' => $this->receptionHour,
            'reception_minute' => $this->receptionMinute,
        ]); 

        $english = Language::where('code', 'en')->first();
        $polish = Language::where('code', 'pl')->first();

        $postingMonthTranslation = MonthTranslation::where('month', $this->postingMonth)->first();

        $englishPostingMonthTranslation = MonthTranslation::where('month_id', $postingMonthTranslation->month_id)
                                                          ->where('language_id', 1)->first();

        $polishPostingMonthTranslation = MonthTranslation::where('month_id', $postingMonthTranslation->month_id)
                                                         ->where('language_id', 2)->first(); 

        $receptionMonthTranslation = MonthTranslation::where('month', $this->receptionMonth)->first();

        $englishReceptionMonthTranslation = MonthTranslation::where('month_id', $receptionMonthTranslation->month_id)
                                                            ->where('language_id', 1)->first();

        $polishReceptionMonthTranslation = MonthTranslation::where('month_id', $receptionMonthTranslation->month_id)
                                                           ->where('language_id', 2)->first(); 


        $translationClient = new TranslationServiceClient();

        $request = new TranslateTextRequest();

        if($this->description)
        {
                          //0              1                         2                3
            $contents = [$this->thing, $this->postingPlace, $this->receptionPlace, $this->description];
        }
        else
        {
                          //0              1                         2 
            $contents = [$this->thing, $this->postingPlace, $this->receptionPlace];
        }

        $request->setTargetLanguageCode('en-US'); //pl-PL | en-US
        $request->setContents($contents); //, $this->description | [$this->thing]
        $request->setParent('projects/postman-338316');

        //$array = []; //test

        try {
            //English
            $response = $translationClient->translateText($request);

            $translations = [];

            foreach ($response->getTranslations() as $key => $translation) {
                $translations[$key] = $translation->getTranslatedText();
            }

            if(count($contents) == 4) //or $this->description == null
            {
                $courier->translations()->create([ 
                    'lang_id' => $english->id,
                    'thing' => $translations[0], //'English thing'
                    'description' => $translations[3], //'English Description'
                    'posting_place' => $translations[1],
                    'reception_place' => $translations[2],
                    'posting_month' => $englishPostingMonthTranslation->month,
                    'reception_month' => $englishReceptionMonthTranslation->month
                ]);
            }
            else
            {
                $courier->translations()->create([ 
                    'lang_id' => $english->id,
                    'thing' => $translations[0], //'English thing'
                    'posting_place' => $translations[1], 
                    'reception_place' => $translations[2],
                    'posting_month' => $englishPostingMonthTranslation->month,
                    'reception_month' => $englishReceptionMonthTranslation->month
                ]);
            } 

            //$array[] = $translations; //test

            //polish
            $request->setTargetLanguageCode('pl-PL');

            $response = $translationClient->translateText($request);

            $translations = [];

            foreach ($response->getTranslations() as $key => $translation) {
                $translations[$key] = $translation->getTranslatedText();
            }

            if(count($contents) == 4) //or $this->description == null
            {
                $courier->translations()->create([ 
                    'lang_id' => $polish->id,
                    'thing' => $translations[0], //'Polish thing'
                    'description' => $translations[3], //'Polish Description'
                    'posting_place' => $translations[1],
                    'reception_place' => $translations[2],
                    'posting_month' => $polishPostingMonthTranslation->month,
                    'reception_month' => $polishReceptionMonthTranslation->month
                ]);
            }
            else
            {
                $courier->translations()->create([ 
                    'lang_id' => $polish->id,
                    'thing' => $translations[0], //'Polish thing'
                    'posting_place' => $translations[1], //'Polish Description'
                    'reception_place' => $translations[2],
                    'posting_month' => $polishPostingMonthTranslation->month,
                    'reception_month' => $polishReceptionMonthTranslation->month
                ]);
            } 

            //$array[] = $translations; //test

        } catch(Exception $e) {
            //no translation
            
            if(count($contents) == 4) //or $this->description == null
            {
                //english
                $courier->translations()->create([ 
                    'lang_id' => $english->id,
                    'thing' => $contents[0], 
                    'description' => $contents[3], 
                    'posting_place' => $contents[1],
                    'reception_place' => $contents[2],
                    'posting_month' => $englishPostingMonthTranslation->month,
                    'reception_month' => $englishReceptionMonthTranslation->month
                ]);

                //polish
                $courier->translations()->create([ 
                    'lang_id' => $polish->id,
                    'thing' => $contents[0], 
                    'description' => $contents[3], 
                    'posting_place' => $contents[1],
                    'reception_place' => $contents[2],
                    'posting_month' => $polishPostingMonthTranslation->month,
                    'reception_month' => $polishReceptionMonthTranslation->month
                ]); 
            }
            else
            {
                //english
                $courier->translations()->create([ 
                    'lang_id' => $english->id,
                    'thing' => $contents[0], 
                    'posting_place' => $contents[1], 
                    'reception_place' => $contents[2],
                    'posting_month' => $englishPostingMonthTranslation->month,
                    'reception_month' => $englishReceptionMonthTranslation->month
                ]);

                //polish
                $courier->translations()->create([ 
                    'lang_id' => $polish->id,
                    'thing' => $contents[0], 
                    'posting_place' => $contents[1], 
                    'reception_place' => $contents[2],
                    'posting_month' => $polishPostingMonthTranslation->month,
                    'reception_month' => $polishReceptionMonthTranslation->month
                ]);
            } 

            //$array[] = $contents; //test
            //$array[] = $contents; //test

            //dd($e);
        }

        //dd($array); 

        if($this->weight)
        {
            if($this->metricOrImperial === 'metric')
            {
                $metricWeight = $this->weight;

                $imperialWeight = ceil($this->weight / 0.45359237);
            }
            else
            {
                $metricWeight = ceil($this->weight * 0.45359237);

                $imperialWeight = $this->weight;
            }
        }
        else
        {
            $metricWeight = null;
            $imperialWeight = null;
        }

        $courier->weights()->create([ 
            'metric_or_imperial' => 'metric',
            'weight' => $metricWeight, 
        ]);

        $courier->weights()->create([ 
            'metric_or_imperial' => 'imperial',
            'weight' => $imperialWeight, 
        ]);


        if($this->dimensionLength)
        {
            if($this->metricOrImperial === 'metric')
            {
                $metricLength = $this->dimensionLength;

                $imperialLength = ceil($this->dimensionLength / 2.54);
            }
            else
            {
                $metricLength = ceil($this->dimensionLength * 2.54);

                $imperialLength = $this->dimensionLength;
            }
        }
        else
        {
            $metricLength = null;
            $imperialLength = null;
        }

        if($this->width)
        {
            if($this->metricOrImperial === 'metric')
            {
                $metricWidth = $this->width;

                $imperialWidth = ceil($this->width / 2.54);
            }
            else
            {
                $metricWidth = ceil($this->width * 2.54);

                $imperialWidth = $this->width;
            }
        }
        else
        {
            $metricWidth = null;
            $imperialWidth = null;
        }

        if($this->height)
        {
            if($this->metricOrImperial === 'metric')
            {
                $metricHeight = $this->height;

                $imperialHeight = ceil($this->height / 2.54);
            }
            else
            {
                $metricHeight = ceil($this->height * 2.54);

                $imperialHeight = $this->height;
            }
        }
        else
        {
            $metricHeight = null;
            $imperialHeight = null;
        }

        $courier->dimensions()->create([ 
            'metric_or_imperial' => 'metric',
            'length' => $metricLength, 
            'width' => $metricWidth,
            'height' => $metricHeight
        ]);

        $courier->dimensions()->create([ 
            'metric_or_imperial' => 'imperial',
            'length' => $imperialLength, 
            'width' => $imperialWidth,
            'height' => $imperialHeight
        ]);

        $this->redirectRoute('couriers-announcements.index');
    }

    public function render()
    {
        return view('livewire.couriers-announcements.create');
    }
}
