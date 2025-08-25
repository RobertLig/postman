<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Mary\Traits\WithMediaSync;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;
use App\Models\MonthTranslation;
//use Google\Cloud\Translate\V2\TranslateClient;
//use Google\Cloud\Translate\V3\TranslateClient;
use Google\Cloud\Translate\V3\Client\TranslationServiceClient;
use Google\Cloud\Translate\V3\TranslateTextRequest;
use App\Models\SenderAnnouncement;

new #[Title('Create senders` announcement')]
class extends Component {
    use WithFileUploads, WithMediaSync;

    #[Validate(['files.*' => 'image|max:1024'])]
    public array $files = []; 

    
    public Collection $library; //#[Validate('required')]

    #[Validate('required|string|max:20')]
    public $thing;

    #[Validate('nullable|string|max:200')]
    public $description;

    #[Validate('required|string|in:metric,imperial')]
    public $metricOrImperial;

    #[Validate('nullable|numeric')]
    public $dimensionLength; //can't be $length name for a property. Alpine.js doesn't accept

    #[Validate('nullable|numeric')]
    public $width;

    #[Validate('nullable|numeric')]
    public $height;

    #[Validate('nullable|numeric')]
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

    #[Validate('required|string|max:200')]
    public string $receptionPlace;

    public function mount(): void
    {
        // Load existing library metadata from your model
        //$this->library = $this->user->library;
 
        // Or ... an empty collection if this component creates a user
        $this->library = new Collection();

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

    /*public function setLength($input) //another option for Carousela component
    {
        $this->dimensionLength = $input;

        //$this->validate(); //for live validation
    }*/

    public function changeSuffix()
    {
        $this->dispatch('metric-or-imperial', metricOrImperial: $this->metricOrImperial);
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

                $allowed = 4;
                $count = count($this->files);

                if ($count > $allowed) {

                    $excess = $count - 4;

                    for($i = 0; $i < $excess; $i++)
                    {
                        $file = $allowed + $i;

                        $validator->errors()->add("files.$file", __('Too many photos')); //attribute name, message
                    }

                    //dd(count($this->files));
                }
            });
        });
    }

    public function save()
    {
        //$this->validate();

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

        $array = []; //test

        try {
            //English
            $response = $translationClient->translateText($request);

            $translations = [];

            foreach ($response->getTranslations() as $key => $translation) {
                $translations[$key] = $translation->getTranslatedText();
            }

            /* if(count($contents) == 4) //or $this->description == null
            {
                $senderAnnouncement->translations()->create([ 
                    'lang_id' => $english->id,
                    'thing' => $translations[0], //'English thing'
                    'description' => $translations[3], //'English Description'
                    'posting_place' => $translations[1],
                    'reception_place' => $translations[2],
                    'posting_month' => '',
                    'reception_month' => ''
                ]);
            }
            else
            {
                $senderAnnouncement->translations()->create([ 
                    'lang_id' => $english->id,
                    'thing' => $translations[0], //'English thing'
                    'posting_place' => $translations[1], //'English Description'
                    'reception_place' => $translations[2],
                    'posting_month' => '',
                    'reception_month' => ''
                ]);
            } */

            $array[] = $translations; //test

            //polish
            $request->setTargetLanguageCode('pl-PL');

            $response = $translationClient->translateText($request);

            $translations = [];

            foreach ($response->getTranslations() as $key => $translation) {
                $translations[$key] = $translation->getTranslatedText();
            }

            /* if(count($contents) == 4) //or $this->description == null
            {
                $enderAnnouncement->translations()->create([ 
                    'lang_id' => $polish->id,
                    'thing' => $translations[0], //'Polish thing'
                    'description' => $translations[3], //'Polish Description'
                    'posting_place' => $translations[1],
                    'reception_place' => $translations[2],
                    'posting_month' => '',
                    'reception_month' => ''
                ]);
            }
            else
            {
                $senderAnnouncement->translations()->create([ 
                    'lang_id' => $polish->id,
                    'thing' => $translations[0], //'Polish thing'
                    'posting_place' => $translations[1], //'Polish Description'
                    'reception_place' => $translations[2],
                    'posting_month' => '',
                    'reception_month' => ''
                ]);
            } */

            $array[] = $translations; //test

        } catch(Exception $e) {
            //no translation
            
            /* if(count($contents) == 4) //or $this->description == null
            {
                //english
                $senderAnnouncement->translations()->create([ 
                    'lang_id' => $english->id,
                    'thing' => $contents[0], 
                    'description' => $contents[3], 
                    'posting_place' => $contents[1],
                    'reception_place' => $contents[2],
                    'posting_month' => '',
                    'reception_month' => ''
                ]);

                //polish
                $senderAnnouncement->translations()->create([ 
                    'lang_id' => $polish->id,
                    'thing' => $contents[0], 
                    'description' => $contents[3], 
                    'posting_place' => $contents[1],
                    'reception_place' => $contents[2],
                    'posting_month' => '',
                    'reception_month' => ''
                ]); 
            }
            else
            {
                //english
                $senderAnnouncement->translations()->create([ 
                    'lang_id' => $english->id,
                    'thing' => $contents[0], 
                    'posting_place' => $contents[1], 
                    'reception_place' => $contents[2],
                    'posting_month' => '',
                    'reception_month' => ''
                ]);

                //polish
                $senderAnnouncement->translations()->create([ 
                    'lang_id' => $polish->id,
                    'thing' => $contents[0], 
                    'posting_place' => $contents[1], 
                    'reception_place' => $contents[2],
                    'posting_month' => '',
                    'reception_month' => ''
                ]);
            } */

            $array[] = $contents; //test
            $array[] = $contents; //test

            //dd($e);
        }

        dd($array);
    }
}; ?>

<div>
    <x-header title="{{ __('Create senders` announcement') }}" subtitle="{{ __('If you would like to send something, please fill out the form and post an ad.') }}" separator />
     
    <x-form wire:submit="save">
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
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
