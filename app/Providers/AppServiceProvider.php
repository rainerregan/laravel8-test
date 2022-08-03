<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Menggunakan components
        // Blade::aliasComponent('components.badge', 'badge'); // Alias doesn't work in Laravel 8+

        // Menggunakan Activity composer untuk membagikan data antar views
        view()->composer('posts.index', ActivityComposer::class);
    }
}
