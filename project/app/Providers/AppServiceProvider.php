<?php

namespace App\Providers;

use App\Contracts\GoogleMapsDirectionsContract;
use App\Services\GoogleMapsDirectionsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app
            ->bind(GoogleMapsDirectionsContract::class, GoogleMapsDirectionsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
