<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Reviews extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct() {}


    public function render(): View|Closure|string
    {
        return <<<'blade'
            <div class="mt-30">

                <h1 class="leading-11 text-3xl font-bold text-center">
                    {{ __('What people are saying') }}
                </h1>

                <div class="grid gap-15 md:grid-cols-2 md:gap-5 max-w-lg mx-auto mt-10">
                    
                    <!-- Empbedsocial -->
                    <div 
                        class="embedsocial-hashtag" 
                        data-ref="af317617db5b793d903a13f5c97bd238059f8095"
                    > 
                        <a class="feed-powered-by-es feed-powered-by-es-slider-img es-widget-branding" 
                           href="https://embedsocial.com/blog/embed-google-reviews/" 
                           target="_blank" 
                           title="Embed Google reviews"
                        > 
                           <img src="https://embedsocial.com/cdn/icon/embedsocial-logo.webp" alt="EmbedSocial" /> 

                           <div class="es-widget-branding-text">Embed Google reviews</div> 
                           
                        </a> 
                    </div> 
                    
                    <script> (function(d, s, id) { var js; if (d.getElementById(id)) {return;} js = d.createElement(s); js.id = id; js.src = "https://embedsocial.com/cdn/ht.js"; d.getElementsByTagName("head")[0].appendChild(js); }(document, "script", "EmbedSocialHashtagScript")); </script> 

                    <!-- TrustBox widget - Review Collector -->
                    <div 
                        class="trustpilot-widget" 
                        data-locale="en-US" 
                        data-template-id="56278e9abfbbba0bdcd568bc" 
                        data-businessunit-id="6939b769ff33838b67961017" 
                        data-style-height="52px" 
                        data-style-width="100%" 
                        data-token="e3877f43-7dde-42a5-bab9-8d0a07221545"
                    >
                        <a href="https://www.trustpilot.com/review/postman.chat" target="_blank" rel="noopener">Trustpilot</a>
                    </div>

                </div>

            </div>
        blade;
    }
}
