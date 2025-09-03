<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Mary\Traits\WithMediaSync;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use Google\Cloud\Translate\V3\Client\TranslationServiceClient;
use Google\Cloud\Translate\V3\TranslateTextRequest;
use App\Models\SenderAnnouncement;
use App\Models\MonthTranslation;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

new #[Title('Edit senders` announcement')]
class extends Component {
    use WithFileUploads, WithMediaSync;

    public SenderAnnouncement $senderannouncement;

    public $language;

    #[Validate(['files.*' => 'nullable|image|max:1024'])]
    public array $files = []; 

    
    public Collection $library; //#[Validate('required')] 

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

    public function mount(SenderAnnouncement $senderannouncement): void //received from route parameter
    {
        //dd($senderannouncement); //route model minding works!
        $this->senderannouncement = $senderannouncement;

        $this->language = Language::where('code', App::currentLocale())->first();

        $this->thing = $this->senderannouncement->translate($this->language->id)->thing;

        //$this->files[] = Storage::url('senders-announcements/k4dgerK0P7XvGLcDQb5NVWPpjzJF01x51wkLVQ18.jpg');

        // Load existing library metadata from your model
        $this->library = $this->senderannouncement->library;
 
        // Or ... an empty collection if this component creates a user
        //$this->library = new Collection();

        $this->description = $this->senderannouncement->translate($this->language->id)->description;

        $this->metricOrImperial = 'metric'; //metric | imperial |could store it in database

        $this->dimensionLength = $this->senderannouncement->getDimension($this->metricOrImperial)->length;

        $this->width = $this->senderannouncement->getDimension($this->metricOrImperial)->width;

        $this->height = $this->senderannouncement->getDimension($this->metricOrImperial)->height;

        $this->weight = $this->senderannouncement->getWeight($this->metricOrImperial)->weight;

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

    /*public function setLength($input) //another option for Carousela component
    {
        $this->dimensionLength = $input;

        //$this->validate(); //for live validation
    }*/

    public function changeSuffix()
    {
        $this->dispatch('metric-or-imperial', metricOrImperial: $this->metricOrImperial);

        $this->dimensionLength = $this->senderannouncement->getDimension($this->metricOrImperial)->length;

        $this->width = $this->senderannouncement->getDimension($this->metricOrImperial)->width;

        $this->height = $this->senderannouncement->getDimension($this->metricOrImperial)->height;

        $this->weight = $this->senderannouncement->getWeight($this->metricOrImperial)->weight;
    }

    //component not working. Couldn't reset properties on Alpine with $wire.entangle() during livewire server roundtrip. Issue not solved
    /*public function updatedPostingMonth()
    {
        if($this->postingYear != null && $this->postingMonth != null)
        {
            $monthTranslationModel = MonthTranslation::where('month', $this->postingMonth)->first();

            $calDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthTranslationModel->month_id, $this->postingYear);

            //if($calDaysInMonth != $this->calDaysInMonth)
            //{
                $this->calDaysInMonth = $calDaysInMonth;

                $this->dispatch('updated-posting-month-year', month: $monthTranslationModel->month_id, year: $this->postingYear, calDaysInMonth: $calDaysInMonth); //monthYearCalDays: [$this->postingMonth, $this->postingYear, $this->calDaysInMonth]

                //$this->dispatch('updated-posting-day', calDaysInMonth: $calDaysInMonth);
            //}
        }

        //dd($calDaysInMonth);
    }

    public function updatedReceptionMonth()
    {
        

        //dd($this->receptionMonth);
    }*/

    public function boot() 
    {   //updatedFiles
        //dd(count($this->files["*"])); //$this->files

        $this->withValidator(function ($validator) {
            $validator->after(function ($validator) {

                //files
                $allowed = 4;
                $count = count($this->files);

                //dd($this->files);

                if ($count > $allowed) {

                    $excess = $count - 4;

                    for($i = 0; $i < $excess; $i++)
                    {
                        $file = $allowed + $i;

                        $validator->errors()->add("files.$file", __('Too many photos')); //attribute name, message
                    }

                    //dd(count($this->files));
                }

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

    /*public function update()//test
    {
        $this->authorize('update', $this->senderannouncement); //maybe not needed?

        dd('all ok');
    } */

    public function update()
    {
        $this->authorize('update', $this->senderannouncement); //maybe not needed?

        $this->validate();

        //dd($this->senderannouncement->library);

        /*if($this->senderannouncement->photo_url_1) //test
        {
            Storage::disk('senders-announcements')->delete($this->senderannouncement->photo_url_1);
        }*/

        $paths = [null, null, null, null];

        //$index = 0;

        foreach($this->files as $file)
        {
            $paths[] = $file->store(options: 'senders-announcements'); //$index

            //$index++;
        }

        $this->syncMedia($this->senderannouncement); //sync media after storing files (syncying before doesn't store files)

        foreach($paths as $key => $path) //reve freshly uploaded files from the folder
        {
            if($path)
            {
                Storage::disk('senders-announcements')->delete($paths[$key]);
            }
        }

        $user = Auth::user();

        $this->senderannouncement->update([
            'user_id' => $user->id,
            /* 'photo_url_1' => $paths[0],
            'photo_url_2' => $paths[1],
            'photo_url_3' => $paths[2],
            'photo_url_4' => $paths[3], */
            'library' => $this->library,
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
                $this->senderannouncement->translations()->where('lang_id', $english->id)->update([ 
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
                $this->senderannouncement->translations()->where('lang_id', $english->id)->update([ 
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
                $this->senderannouncement->translations()->where('lang_id', $polish->id)->update([ 
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
                $this->senderannouncement->translations()->where('lang_id', $polish->id)->update([ 
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
                $this->senderannouncement->translations()->where('lang_id', $english->id)->update([ 
                    'lang_id' => $english->id,
                    'thing' => $contents[0], 
                    'description' => $contents[3], 
                    'posting_place' => $contents[1],
                    'reception_place' => $contents[2],
                    'posting_month' => $englishPostingMonthTranslation->month,
                    'reception_month' => $englishReceptionMonthTranslation->month
                ]);

                //polish
                $this->senderannouncement->translations()->where('lang_id', $polish->id)->update([ 
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
                $this->senderannouncement->translations()->where('lang_id', $english->id)->update([ 
                    'lang_id' => $english->id,
                    'thing' => $contents[0], 
                    'posting_place' => $contents[1], 
                    'reception_place' => $contents[2],
                    'posting_month' => $englishPostingMonthTranslation->month,
                    'reception_month' => $englishReceptionMonthTranslation->month
                ]);

                //polish
                $this->senderannouncement->translations()->where('lang_id', $polish->id)->update([ 
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

        $this->senderannouncement->weights()->where('metric_or_imperial', 'metric')->update([ 
            'metric_or_imperial' => 'metric',
            'weight' => $metricWeight, 
        ]);

        $this->senderannouncement->weights()->where('metric_or_imperial', 'imperial')->update([ 
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

        $this->senderannouncement->dimensions()->where('metric_or_imperial', 'metric')->update([ 
            'metric_or_imperial' => 'metric',
            'length' => $metricLength, 
            'width' => $metricWidth,
            'height' => $metricHeight
        ]);

        $this->senderannouncement->dimensions()->where('metric_or_imperial', 'imperial')->update([ 
            'metric_or_imperial' => 'imperial',
            'length' => $imperialLength, 
            'width' => $imperialWidth,
            'height' => $imperialHeight
        ]);

        $this->redirectRoute('senders-announcements.index');
    }
}; ?>

<div>
    <x-header title="{{ __('Edit your announcement') }}" subtitle="{{ __('You can make some changes in the fields below.') }}" separator />
     
    <x-form wire:submit="update">
        <x-input label="{{ __('A thing') }}" wire:model.live="thing" placeholder="{{ __('A thing') }}" icon="o-question-mark-circle"  clearable /> 

        <x-hr target="thing" />

        <x-image-library
            wire:model="files"                 {{-- Temprary files --}}
            wire:library="library"             {{-- Library metadata property --}}
            :preview="$library"                {{-- Preview control --}}
            label="{{ __('Photos of the item') }}"
            hint="{{ __('Max 4 photos') }}" 
            add-files-text="{{ __('Add images') }}" 
            crop-title-text="{{ __('Crop image') }}" 
            crop-cancel-text="{{ __('Cancel') }}"
            crop-save-text="{{ __('Crop') }}"
            crop-text="{{ __('Crop') }}"
            remove-text="{{ __('Remove') }}" 
            change-text="{{ __('Change') }}" />

        <x-textarea label="{{ __('Item description') }}" wire:model.live="description" placeholder="{{ __('Item description') }}" hint="{{ __('Max 200 chars') }}" rows="5" />

        <x-hr target="description" />

        <x-dimensions-weight label="{{ __('Dimensions and weight') }}" /> 

        <x-place-autocomplete />

        {{-- <x-map /> --}}
        
        <x-create-resource-section label="{{ __('Posting date and hour') }}" class="sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 max-w-3xl" > {{-- sm:grid-cols-2 xl:grid-cols-3 max-w-3xl --}}
            
            {{-- <livewire:announcement.post-day /> component not working. Couldn't reset properties on Alpine with $wire.entangle() during livewire server roundtrip. Issue not solved--}}

            <x-carousela class="" :data-carousel="$dataDay" input="{{ $currentDay }}" total-value="{{ $calDaysInMonth }}" start-value="1" model-name="postingDay" is-live="true"  
                prefix-zero="false" :text-values="$textValuesDay" > 
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Day') }}" wire:model.live="postingDay" placeholder="{{ __('Day') }}" clearable /> 
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingDay" /> 
                </x-slot:progress> 
            </x-carousela> 

            <x-carousela class="w-25" :data-carousel="$dataMonth" input="{{ $currentMonth }}" total-value="11" start-value="0" model-name="postingMonth" is-live="true"  
                prefix-zero="false" :text-values="$textValuesMonth" > 
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Month') }}" wire:model.live="postingMonth" placeholder="{{ __('Month') }}" clearable /> 
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingMonth" /> 
                </x-slot:progress> 
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataYear" input="{{ $currentYear }}" total-value="{{ $currentYear + 17 }}" start-value="{{ $currentYear - 1 }}" model-name="postingYear" is-live="true"  
                prefix-zero="false" :text-values="$textValuesYear" > 
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Year') }}" wire:model.live="postingYear" placeholder="{{ __('Year') }}" clearable /> 
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingYear" /> 
                </x-slot:progress> 
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataHour" input="{{ $currentHour }}" total-value="23" start-value="0" model-name="postingHour" is-live="true"  
                prefix-zero="false" :text-values="$textValuesHour" > 
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Hour') }}" wire:model.live="postingHour" placeholder="{{ __('Hour') }}" clearable /> 
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingHour" /> 
                </x-slot:progress> 
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataMinute" input="{{ $currentMinute }}" total-value="59" start-value="0" model-name="postingMinute" is-live="true"  
                prefix-zero="true" :text-values="$textValuesMinute" > 
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Minute') }}" wire:model.live="postingMinute" placeholder="{{ __('Minute') }}" clearable /> 
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingMinute" /> 
                </x-slot:progress> 
            </x-carousela> 

        </x-create-resource-section>

        <x-create-resource-section label="{{ __('Reception date and hour') }}" class="sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 max-w-3xl" >
            
            <x-carousela class="" :data-carousel="$dataDay" input="{{ $currentDay }}" total-value="{{ $calDaysInMonth }}" start-value="1" model-name="receptionDay" is-live="true"  
                prefix-zero="false" :text-values="$textValuesDay" > 
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Day') }}" wire:model.live="receptionDay" placeholder="{{ __('Day') }}" clearable /> 
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionDay" /> 
                </x-slot:progress> 
            </x-carousela>

            <x-carousela class="w-25" :data-carousel="$dataMonth" input="{{ $currentMonth }}" total-value="11" start-value="0" model-name="receptionMonth" is-live="true"  
                prefix-zero="false" :text-values="$textValuesMonth" > 
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Month') }}" wire:model.live="receptionMonth" placeholder="{{ __('Month') }}" clearable /> 
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionMonth" /> 
                </x-slot:progress> 
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataYear" input="{{ $currentYear }}" total-value="{{ $currentYear + 17 }}" start-value="{{ $currentYear - 1 }}" model-name="receptionYear" is-live="true"  
                prefix-zero="false" :text-values="$textValuesYear" > 
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Year') }}" wire:model.live="receptionYear" placeholder="{{ __('Year') }}" clearable /> 
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionYear" /> 
                </x-slot:progress> 
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataHour" input="{{ $currentHour }}" total-value="23" start-value="0" model-name="receptionHour" is-live="true"  
                prefix-zero="false" :text-values="$textValuesHour" > 
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Hour') }}" wire:model.live="receptionHour" placeholder="{{ __('Hour') }}" clearable /> 
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionHour" /> 
                </x-slot:progress> 
            </x-carousela>

            <x-carousela class="" :data-carousel="$dataMinute" input="{{ $currentMinute }}" total-value="59" start-value="0" model-name="receptionMinute" is-live="true"  
                prefix-zero="true" :text-values="$textValuesMinute" > 
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Minute') }}" wire:model.live="receptionMinute" placeholder="{{ __('Minute') }}" clearable /> 
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="receptionMinute" /> 
                </x-slot:progress> 
            </x-carousela>

        </x-create-resource-section> 

        <x-slot:actions>
            <x-button label="{{ __('Update') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="update" />
        </x-slot:actions>
    </x-form>
</div>

