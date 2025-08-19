<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\App;

class Map extends Component
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
            <div x-data="{
                map: null,

                placeObject: {
                    AutocompleteSessionToken: null,
                    AutocompleteSuggestion: null
                },

                resultsContainerElement: null,
                inputElement: null,
                newestRequestId: 0,

                propertyName: '',

                request: {
                    input: '',
                    //language: 'en-US',
                },
                
                async initMap() 
                {
                    const { Map } = await google.maps.importLibrary('maps');

                    this.placeObject = await google.maps.importLibrary('places');

                    map = new Map(document.getElementById('map'), {
                        center: { lat: 52.216667, lng: 21.033333 },
                        zoom: 5,
                        minZoom: 1,
                        //maxZoom: 20,
                        mapTypeControl: true,
                        mapTypeControlOptions: {
                            //style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                            position: google.maps.ControlPosition.LEFT_BOTTOM,
                        },
                        gestureHandling: 'cooperative',
                    });

                    //map.controls[google.maps.ControlPosition.TOP_LEFT].push($refs.robertcard); //inputs flicker on server request

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

                    this.resultsContainerElement = this.propertyName == 'postingPlace' ? $refs.postingPlaceResults : $refs.receptionPlaceResults;

                    // Reset elements and exit if an empty string is received.
                    if($event.target.value == '') 
                    {
                        this.resultsContainerElement.replaceChildren();

                        return;
                    }

                    // Add the latest char sequence to the request.
                    this.request.input = $event.target.value;

                    // To avoid race conditions, store the request ID and compare after the request.
                    const requestId = ++this.newestRequestId;

                    // Fetch autocomplete suggestions and show them in a list.
                    const { suggestions } = await this.placeObject.AutocompleteSuggestion.fetchAutocompleteSuggestions(this.request); //await google.maps.places.AutocompleteSuggestion.fetchAutocompleteSuggestions(this.request)
                    
                    console.log(this.request); //$event.target.value | $wire.postingPlace
                },

                refreshToken() //request
                {
                    // Create a new session token and add it to the request.
                    this.request.sessionToken = new this.placeObject.AutocompleteSessionToken(); //new google.maps.places.AutocompleteSessionToken()

                    console.log(this.request.sessionToken);
                }
            }" >
                <div class="relative">
                    <div wire:ignore id="map" class="h-100 "></div>

                    <div x-ref="robertcard" class="absolute top-0  grid sm:gap-x-5 sm:grid-cols-2 w-70 sm:w-lg md:w-2xl lg:w-xl xl:w-3xl ps-2"> {{-- max-w-3xl --}}
                        <div>
                            <x-map-input label="{{ __('Posting place') }}" wire:model.live="postingPlace" @input="makeAutocompleteRequest" placeholder="{{ __('Posting place') }}" clearable  /> {{-- class="!w-max" --}}

                            <ul x-ref="postingPlaceResults" class="list bg-base-100 rounded-box shadow-md"></ul>

                            <x-hr target="postingPlace" />
                        </div>
                        <div>
                            <x-map-input label="{{ __('Reception place') }}" wire:model.live="receptionPlace" @input="makeAutocompleteRequest" placeholder="{{ __('Reception place') }}" clearable />

                            <ul x-ref="receptionPlaceResults" class="list bg-base-100 rounded-box shadow-md"></ul>

                            <x-hr target="receptionPlace" />
                        </div>
                    </div>
                </div>

                <script x-init="initMap()"> {{-- libraries: "places", --}}
                    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
                        key: "AIzaSyAjMNO6SHx4PGMiL1TD0seH09jA0T3JOVY",
                        v: "weekly",
                        quotaUser: "{{ auth()->user()->id }}",                       
                        language: "{{ App::currentLocale() }}"
                        // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
                        // Add other bootstrap parameters as needed, using camel case.
                    });
                </script> 

                {{-- <script>
                    function initMap() 
                {
                    let location = {lat: 41.871941, lng: 12.567380}; /*-25.363; 131.044*/
                    let map = new google.maps.Map(document.querySelector('#map'), { 
                        center: location,
                        zoom: 5, 
                        disableDefaultUI: true,
                        gestureHandling: 'cooperative'
                    });
                }
                </script>

                <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjMNO6SHx4PGMiL1TD0seH09jA0T3JOVY&libraries=places&callback=initMap&v=weekly&quotaUser='<?php //echo $_SESSION["me_id"] ?>'">
                </script> --}}
            </div>
        blade;
    }
}
