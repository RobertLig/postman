<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Sender;
use App\Policies\SenderPolicy;
use Illuminate\Support\Facades\Gate;
use App\Services\Places\Contracts\PlaceAutocompleteServiceInterface;
use App\Services\Places\PhotonPlaceAutocompleteService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PlaceAutocompleteServiceInterface::class,
            PhotonPlaceAutocompleteService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Sender::class, SenderPolicy::class);

        // Force secure URLs, asset links, and signatures on the live production server
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Environment safety net for missing host environments (like server CLI/Queues)
        if (app()->runningInConsole() || !request()->headers->has('host')) {
            \Illuminate\Support\Facades\URL::defaults([
                'scheme' => 'https', // Force HTTPS scheme fallback
                'domain' => 'www.postman.chat'
            ]);
            request()->headers->set('host', 'www.postman.chat');

            // Force the underlying request container to mark the transaction as secure
            request()->server->set('HTTPS', 'on');
        }
    }
}
