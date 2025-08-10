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

    public $totalValue;

    public $postingDay;
    public array $dataDay;
    public array $textValuesDay;

    public function mount(): void
    {
        // Load existing library metadata from your model
        //$this->library = $this->user->library;
 
        // Or ... an empty collection if this component creates a user
        $this->library = new Collection();

        $this->metricOrImperial = 'metric';

        $this->dataDay = [1, 2, 3, 4, 5, 28, 29, 30, 31];


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
            <x-carousela class="" :data-carousel="$dataDay" input="1" total-value="31" start-value="1" model-name="postingDay" is-live="true"  
                prefix-zero="false" :text-values="$textValuesDay" > {{-- set-property-method="setLength" --}}
                            
                <x-slot:input-element>
                    <x-input label="{{ __('Length') }}" wire:model.live="postingDay" placeholder="{{ __('Length') }}" clearable /> {{-- x-model="inputPlaceholder" --}}
                </x-slot:input-element>

                <x-slot:progress>
                    <x-hr target="postingDay" /> {{-- setLength; can be set to both property name and action name --}}
                </x-slot:progress> 
            </x-carousela>
        </x-create-resource-section>

        <x-slot:actions>
            <x-button label="{{ __('Save') }}" icon="o-paper-airplane" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
