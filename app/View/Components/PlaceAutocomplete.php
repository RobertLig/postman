<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PlaceAutocomplete extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div class="grid sm:gap-x-5 sm:grid-cols-2 max-w-3xl" x-data="{
                
                resultsContainerElement: null,
        
                newestRequestId: 0,

                propertyName: '',

                activeInputElement: null,

                async makeAutocompleteRequest($event, propertyName)
                {
                    this.propertyName = propertyName;

                    this.activeInputElement = $event.target;

                    if($event.target.value.trim().length < 2)
                    {
                        this.closeResultsContainerElement();

                        return;
                    }

                    this.closeResultsContainerElement();

                    this.resultsContainerElement = this.propertyName == 'postingPlace' ? $refs.postingPlaceResults : $refs.receptionPlaceResults;

                    // Reset elements and exit if an empty string is received.
                    if($event.target.value == '') 
                    {
                        this.closeResultsContainerElement();

                        return;
                    }

                    // To avoid race conditions, store the request ID and compare after the request.
                    const requestId = ++this.newestRequestId;

                    // Fetch autocomplete suggestions and show them in a list.
                    try
                    {
                        const response = await fetch(
                            `/place-autocomplete?q=${encodeURIComponent(
                                $event.target.value
                            )}`
                        );

                        const suggestions = await response.json();

                        if(suggestions.length === 0)
                        {
                            this.closeResultsContainerElement();

                            return;
                        }
                    
                        // If the request has been superseded by a newer request, do not render the output.
                        if(requestId !== this.newestRequestId)
                        {
                            return;
                        }

                        // Clear the list first.
                        this.resultsContainerElement.replaceChildren();

                        this.resultsContainerElement.classList.add('border-[length:var(--border)]');

                        for (const suggestion of suggestions)
                        {
                            const li = document.createElement('li');

                            li.classList.add(
                                'p-2',
                                'w-full',
                                'border-b',
                                'border-base-200',
                                'cursor-pointer',
                                'hover:bg-base-200'
                            );

                            li.innerText = suggestion.label;

                            li.addEventListener('click', () => {
                                this.onPlaceSelected(suggestion);
                            });

                            this.resultsContainerElement.appendChild(li);
                        }
                    }
                    catch(error)
                    {
                        console.error('Autocomplete error:', error);
                    }

                    //console.log(this.request); //$event.target.value | $wire.postingPlace
                },

                onPlaceSelected(place)
                {
                    this.activeInputElement.value = place.label;

                    $wire.set(
                        this.propertyName,
                        place.label,
                        false //true
                    );

                    this.closeResultsContainerElement();
                },

                closeResultsContainerElement()
                {
                    if(this.resultsContainerElement != null)
                    {
                        this.resultsContainerElement.replaceChildren();

                        this.resultsContainerElement.classList.remove('border-[length:var(--border)]');
                    }
                }
            }" > 
                <div class="relative">
                    <x-map-input label="{{ __('Posting place') }}" wire:model="postingPlace" @input.debounce.300ms="makeAutocompleteRequest($event, 'postingPlace')" placeholder="{{ __('Posting place') }}" clearable  /> 

                    <ul wire:ignore x-ref="postingPlaceResults" class="list absolute rounded-lg shadow border-base-content/10 bg-base-100 z-1 w-full"></ul> {{-- shadow-md --}}

                    <x-hr target="postingPlace" />
                </div>
                <div class="relative">
                    <x-map-input label="{{ __('Reception place') }}" wire:model="receptionPlace" @input.debounce.300ms="makeAutocompleteRequest($event, 'receptionPlace')" placeholder="{{ __('Reception place') }}" clearable />

                    <ul wire:ignore x-ref="receptionPlaceResults" class="list absolute rounded-lg shadow border-base-content/10 bg-base-100 z-1 w-full"></ul> {{-- shadow-md --}}

                    <x-hr target="receptionPlace" />
                </div>
            </div> 
        blade;
    }
}
