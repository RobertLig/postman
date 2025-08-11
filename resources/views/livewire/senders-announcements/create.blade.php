<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use Mary\Traits\WithMediaSync;
use Illuminate\Support\Collection;
use Livewire\Attributes\Validate;

new #[Title('Create senders` announcement')]
class extends Component {
    use WithFileUploads, WithMediaSync;

    #[Validate(['files.*' => 'image|max:1024'])]
    public array $files = [];

    #[Validate('required')]
    public Collection $library;

    public $thing;

    public $description;

    public $metricOrImperial;

    #[Validate(['image'])]
    public $dimensionLength; //can't be $length name for a property. Alpine.js doesn't accept

    #[Validate(['image'])]
    public $width;

    #[Validate(['image'])]
    public $height;

    #[Validate(['image'])]
    public $weight;

    #[Validate(['image'])]
    public $postingDay;

    #[Validate(['image'])]
    public $receptionDay;

    public array $dataDay; 
    public $textValuesDay;
    public int $currentDay;
    public int $calDaysInMonth;

    #[Validate(['image'])]
    public $postingMonth;

    public array $dataMonth;
    public $textValuesMonth;
    public string $currentMonth;

    #[Validate(['image'])]
    public $postingYear;

    public array $dataYear;
    public $textValuesYear;
    public string $currentYear;

    #[Validate(['image'])]
    public $postingHour;

    public array $dataHour;
    public $textValuesHour;
    public string $currentHour;

    #[Validate(['image'])]
    public $postingMinute;

    public array $dataMinute;
    public $textValuesMinute;
    public string $currentMinute;

    public function mount(): void
    {
        // Load existing library metadata from your model
        //$this->library = $this->user->library;
 
        // Or ... an empty collection if this component creates a user
        $this->library = new Collection();

        $this->metricOrImperial = 'metric';

        //day
        $this->currentDay = date("j", mktime(0,0,0, date("n"), date("j"), date("Y")));

        $this->calDaysInMonth = cal_days_in_month(CAL_GREGORIAN, date("n"), date("Y"));

        //must have 9 elements for Carousela component logic
        $this->dataDay = [
            $this->currentDay, 
            date("j", mktime(0,0,0, date("n"), date("j") + 1, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 2, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 3, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") + 4, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 4, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 3, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 2, date("Y"))), 
            date("j", mktime(0,0,0, date("n"), date("j") - 1, date("Y")))
        ];

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
            __( date("Y", mktime(0,0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 1)) ), 
            __( date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 2)) ), 
            __( date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 3)) ), 
            __( date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 4)) ), 
            __( date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 15)) ), 
            __( date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 16)) ), 
            __( date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") + 17)) ), 
            __( date("Y", mktime(0,0,0, date("n"), date("j"), date("Y") - 1)) )
        ]; 

        //hour
        $this->currentHour = date("G", mktime(date("G"),0,0, date("n"), date("j"), date("Y")));

        $this->dataHour = [
            __( date("G", mktime(date("G"),0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("G", mktime(date("G") + 1,0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("G", mktime(date("G") + 2,0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("G", mktime(date("G") + 3,0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("G", mktime(date("G") + 4,0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("G", mktime(date("G") - 4,0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("G", mktime(date("G") - 3,0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("G", mktime(date("G") - 2,0,0, date("n"), date("j"), date("Y"))) ), 
            __( date("G", mktime(date("G") - 1,0,0, date("n"), date("j"), date("Y"))) )
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
}; ?>

<div>
    <x-header title="{{ __('Create senders` announcement') }}" subtitle="{{ __('If you would like to send something, please fill out the form and post an ad.') }}" separator />
     
    <x-form wire:submit="save">
        <x-input label="{{ __('A thing') }}" wire:model="thing" placeholder="{{ __('A thing') }}" icon="o-question-mark-circle"  clearable /> 

        <x-image-library
            wire:model="files"                 {{-- Temprary files --}}
            wire:library="library"             {{-- Library metadata property --}}
            :preview="$library"                {{-- Preview control --}}
            label="{{ __('Photos of the item') }}"
            hint="{{ __('Max 100Kb') }}" 
            add-files-text="{{ __('Add images') }}" 
            crop-title-text="{{ __('Crop image') }}" 
            crop-cancel-text="{{ __('Cancel') }}"
            crop-save-text="{{ __('Crop') }}"
            crop-text="{{ __('Crop') }}"
            remove-text="{{ __('Remove') }}" />

        <x-textarea label="{{ __('Item description') }}" wire:model="description" placeholder="{{ __('Item description') }}" hint="{{ __('Max 200 chars') }}" rows="5" />

        <x-dimensions-weight label="{{ __('Dimensions and weight') }}" /> 

        <x-create-resource-section label="{{ __('Posting date and hour') }}" class="sm:grid-cols-2 xl:grid-cols-3 max-w-3xl" >
            
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

        <x-create-resource-section label="{{ __('Reception date and hour') }}" class="sm:grid-cols-2 xl:grid-cols-3 max-w-3xl" >
            
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

        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
