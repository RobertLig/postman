<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Models\SenderAnnouncement;
use App\Models\Courier;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate a sitemap';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $locales = LaravelLocalization::getSupportedLocales();
        $sitemap = Sitemap::create();

        $staticRoutes = [
            'routes.confirm-password',
            'routes.cookie-policy',
            'routes.register',
            'routes.login',
            'routes.messages',
            'routes.about',
            'routes.contact',
            'routes.faq',
            'routes.forgot-password',
            'routes.privacy-policy',
            'routes.senders-announcements',
            'routes.senders-announcements-create',
            'routes.couriers-announcements-create',
            'routes.couriers-announcements',
            'routes.settings',
            'routes.settings-password',
            'routes.settings-profile',
            'routes.terms-of-use',
            'routes.users',
            'routes.verify-email',
            // Add more as needed
        ];

        foreach ($locales as $localeCode => $properties) {
            foreach ($staticRoutes as $routeKey) {
                $url = LaravelLocalization::getURLFromRouteNameTranslated($localeCode, $routeKey);
                $sitemap->add(Url::create($url));
            }
        }

        foreach (SenderAnnouncement::all() as $senderAnnouncement) {
            foreach ($locales as $localeCode => $properties) {
                $showUrl = LaravelLocalization::getURLFromRouteNameTranslated(
                    $localeCode,
                    'routes.senders-announcements-show',
                    ['senderannouncement' => $senderAnnouncement->id]
                );
                $sitemap->add(Url::create($showUrl));

                $editUrl = LaravelLocalization::getURLFromRouteNameTranslated(
                    $localeCode,
                    'routes.senders-announcements-edit',
                    ['senderannouncement' => $senderAnnouncement->id]
                );
                $sitemap->add(Url::create($editUrl));
            }
        }

        foreach (Courier::all() as $courier) {
            foreach ($locales as $localeCode => $properties) {
                $showUrl = LaravelLocalization::getURLFromRouteNameTranslated(
                    $localeCode,
                    'routes.couriers-announcements-show',
                    ['courier' => $courier->id]
                );
                $sitemap->add(Url::create($showUrl));

                $editUrl = LaravelLocalization::getURLFromRouteNameTranslated(
                    $localeCode,
                    'routes.couriers-announcements-edit',
                    ['courier' => $courier->id]
                );
                $sitemap->add(Url::create($editUrl));
            }
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
