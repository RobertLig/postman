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
    }
}
