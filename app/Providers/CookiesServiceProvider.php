<?php

namespace App\Providers;

use Whitecube\LaravelCookieConsent\CookiesServiceProvider as ServiceProvider;
use Whitecube\LaravelCookieConsent\Facades\Cookies;
use Whitecube\LaravelCookieConsent\Consent;

class CookiesServiceProvider extends ServiceProvider
{
    /**
     * Define the cookies users should be aware of.
     */
    protected function registerCookies(): void
    {
        // Register Laravel's base cookies under the "required" cookies section:
        Cookies::essentials()
            ->session()
            ->csrf();

        // Register all Analytics cookies at once using one single shorthand method:
         Cookies::analytics()
            ->google(
                 id: config('cookieconsent.google_analytics.id'),
                 anonymizeIp: config('cookieconsent.google_analytics.anonymize_ip')
            );

        // Marketing (Google Ads)
        $googleAds = Cookies::category(key: 'google-ads');

        Cookies::googleAds()
            ->name('Google Ads')
            ->duration(365 * 24 * 60);   //minutes
                
        // Documentation doesn't say how to implement MyDarkmod class
        // Register custom cookies under the pre-existing "optional" category:
        // Cookies::optional()
        //     ->name('darkmode_enabled')
        //     ->description('This cookie helps us remember your preferences regarding the interface\'s brightness.')
        //     ->duration(120)
        //     ->accepted(fn(Consent $consent, MyDarkmode $darkmode) => $consent->cookie(value: $darkmode->getDefaultValue()));
    }
}
