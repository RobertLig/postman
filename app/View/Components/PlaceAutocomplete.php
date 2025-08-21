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
                placeObject: {
                    AutocompleteSessionToken: null,
                    AutocompleteSuggestion: null
                },

                resultsContainerElement: null,
                //inputElement: null,
                newestRequestId: 0,

                propertyName: '',

                request: {
                    input: '',
                    language: '{{ App::currentLocale() }}',
                },
                
                async initPlace() 
                {
                    this.placeObject = await google.maps.importLibrary('places');
                    
                    this.refreshToken(); //this.request
                }, 

                async makeAutocompleteRequest($event) {
                    if($event.target.hasAttribute('wire:model'))
                    {
                        this.propertyName = $event.target.getAttribute('wire:model');
                    }
                    else if($event.target.hasAttribute('wire:model.live'))
                    {
                        this.propertyName = $event.target.getAttribute('wire:model.live');
                    }

                    this.closeResultsContainerElement();

                    this.resultsContainerElement = this.propertyName == 'postingPlace' ? $refs.postingPlaceResults : $refs.receptionPlaceResults;

                    // Reset elements and exit if an empty string is received.
                    if($event.target.value == '') 
                    {
                        this.closeResultsContainerElement();

                        return;
                    }

                    // Add the latest char sequence to the request.
                    this.request.input = $event.target.value;

                    // To avoid race conditions, store the request ID and compare after the request.
                    const requestId = ++this.newestRequestId;

                    // Fetch autocomplete suggestions and show them in a list.
                    try
                    {
                        const { suggestions } = await this.placeObject.AutocompleteSuggestion.fetchAutocompleteSuggestions(this.request); //await google.maps.places.AutocompleteSuggestion.fetchAutocompleteSuggestions(this.request);
                    

                    // If the request has been superseded by a newer request, do not render the output.
                    if(requestId !== this.newestRequestId)
                    {
                        return;
                    }

                    // Clear the list first.
                    this.resultsContainerElement.replaceChildren();

                    this.resultsContainerElement.classList.add('border-[length:var(--border)]');

                    for (const suggestion of suggestions) {
                        const placePrediction = suggestion.placePrediction;
        
                        const li = document.createElement('li');

                        li.classList.add('p-2', 'w-full', 'border-b', 'border-base-200', 'cursor-default'); //'[&:not(:last-child)]:border-b' | '[&:not(:last-child)]:mb-1', '[&:first-child]:rounded-ss-lg', '[&:first-child]:rounded-se-lg', '[&:last-child]:rounded-es-lg', '[&:last-child]:rounded-ee-lg', 'shadow', 'border-[length:var(--border)]', 'border-base-content/10', 'bg-base-100'

                        li.addEventListener('click', () => {
                            this.onPlaceSelected(placePrediction.toPlace());
                        });

                        li.innerText = placePrediction.text.toString();
                        
                        this.resultsContainerElement.appendChild(li);
                    }

                    const img = document.createElement('img');

                    img.classList.add('powered-by-google', 'h-5', 'w-10');

                    img.src='https://storage.googleapis.com/geo-devrel-public-buckets/powered_by_google_on_white.png';

                    img.alt='Powered by Google';

                    const li = document.createElement('li');

                    li.classList.add('p-2', 'w-full');

                    li.appendChild(img);

                    this.resultsContainerElement.appendChild(li);

                    }
                    catch(error)
                    {
                        console.log('Too many autocomplete requests'); 
                    }

                    //console.log(this.request); //$event.target.value | $wire.postingPlace
                },

                async onPlaceSelected(place) 
                {
                    try
                    {
                        await place.fetchFields({ fields: ['displayName', 'formattedAddress'], });
                    }
                    catch(error)
                    {
                        console.log('pick place error');
                    }

                    const placeText = place.displayName + ' ' + place.formattedAddress; 

                    $wire.set( this.propertyName, placeText, true );

                    this.closeResultsContainerElement();

                    this.refreshToken();

                    //console.log(place);  
                },

                refreshToken() //request
                {
                    // Create a new session token and add it to the request.
                    this.request.sessionToken = new this.placeObject.AutocompleteSessionToken(); //new google.maps.places.AutocompleteSessionToken()

                    //console.log(this.request.sessionToken);
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
                    <x-map-input label="{{ __('Posting place') }}" wire:model.live="postingPlace" @input="makeAutocompleteRequest" placeholder="{{ __('Posting place') }}" clearable  /> 

                    <ul wire:ignore x-ref="postingPlaceResults" class="list absolute rounded-lg shadow border-base-content/10 bg-base-100 z-1 w-full"></ul> {{-- shadow-md --}}

                    <x-hr target="postingPlace" />
                </div>
                <div class="relative">
                    <x-map-input label="{{ __('Reception place') }}" wire:model.live="receptionPlace" @input="makeAutocompleteRequest" placeholder="{{ __('Reception place') }}" clearable />

                    <ul wire:ignore x-ref="receptionPlaceResults" class="list absolute rounded-lg shadow border-base-content/10 bg-base-100 z-1 w-full"></ul> {{-- shadow-md --}}

                    <x-hr target="receptionPlace" />
                </div>

                <script x-init="initPlace()"> 
                    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
                        key: "AIzaSyAjMNO6SHx4PGMiL1TD0seH09jA0T3JOVY",
                        v: "weekly",
                        quotaUser: "{{ auth()->user()->id }}",                       
                        language: "{{ App::currentLocale() }}"
                        // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
                        // Add other bootstrap parameters as needed, using camel case.
                    });
                </script> 
            </div> 
        blade;
    }
}
