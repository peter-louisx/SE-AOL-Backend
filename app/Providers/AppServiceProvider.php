<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Xendit\Configuration;

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
        // Set Xendit API key from environment variable
        Configuration::setXenditKey(
            env('XENDIT_SECRET_KEY', 'default_api_key')
        );
    }
}
