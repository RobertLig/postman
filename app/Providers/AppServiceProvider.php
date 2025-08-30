<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\SenderAnnouncement;
use App\Policies\SenderAnnouncementPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(SenderAnnouncement::class, SenderAnnouncementPolicy::class);
    }
}
