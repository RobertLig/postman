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
                
                async initMap() 
                {
                    const { Map } = await google.maps.importLibrary('maps');

                    map = new Map(document.getElementById('map'), {
                        center: { lat: 52.216667, lng: 21.033333 },
                        zoom: 5,
                    });
                }, 

                
            }" >
                <div id="map" class="h-100 "></div>

                <script x-init="initMap()">
                    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
                        key: "AIzaSyAjMNO6SHx4PGMiL1TD0seH09jA0T3JOVY",
                        v: "weekly",
                        quotaUser: "{{ auth()->user()->id }}",
                        libraries: "places",
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
