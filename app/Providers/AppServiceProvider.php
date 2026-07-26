<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\BlankSpot;
use App\Policies\BlankSpotPolicy;
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
        Gate::policy(BlankSpot::class, BlankSpotPolicy::class);
    }
}
