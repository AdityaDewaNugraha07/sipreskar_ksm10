<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        $currentHost = request()->getHost();

        // Kalau lagi diakses lewat ngrok (atau domain HTTPS), paksa HTTPS
        if (str_contains($currentHost, 'ngrok-free.dev')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Carbon::setLocale('id');

        // Kalau diakses lewat localhost, biarkan default (HTTP)
    }
}
