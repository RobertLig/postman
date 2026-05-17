<div>
    @if (request()->routeIs('home') && \Whitecube\LaravelCookieConsent\Facades\Cookies::hasConsentFor('analytics'))
        <script>
            window.addEventListener('load', function() {

                if (document.getElementById('EmbedSocialHashtagScript')) {
                    return;
                }

                let script = document.createElement('script');

                script.id = 'EmbedSocialHashtagScript';

                script.src = 'https://embedsocial.com/cdn/ht.js';

                document.head.appendChild(script);
            });
        </script>
    @endif
</div>
