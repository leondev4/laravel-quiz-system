<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        Blade::if('admin', function() {
            return auth()->user()?->is_admin;
        });
         Blade::if('noadmin', function() {
            return ! auth()->user()?->is_admin;
        });
        /*descomentar para production
        y en .env colocar APP_ENV=production
        */
    
        // \URL::forceScheme('https'); 
    }
}
