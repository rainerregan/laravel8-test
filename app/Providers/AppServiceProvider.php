<?php

namespace App\Providers;

use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
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
        // view()->composer('*', ActivityComposer::class); // Untuk semua views
        view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);

        // Schema
        Schema::defaultStringLength(191);
    }
}
