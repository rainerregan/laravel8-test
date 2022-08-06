<?php

namespace App\Providers;

use App\Contracts\CounterContract;
use App\Http\ViewComposers\ActivityComposer;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Observers\BlogPostObserver;
use App\Observers\CommentObserver;
use App\Services\Counter;
use App\Services\DummyCounter;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

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

        // Using observer
        BlogPost::observe(BlogPostObserver::class);
        Comment::observe(CommentObserver::class);

        // Service container
        $this->app->singleton(Counter::class, function($app) {
            return new Counter(
                $app->make(Factory::class),
                $app->make(Session::class),
                env('COUNTER_TIMEOUT')
            );
        });

        // $this->app->when(Counter::class)->needs('$timeout')->give(env('COUNTER_TIMEOUT'));

        // Contract
        $this->app->bind(
            CounterContract::class,
            Counter::class
        );

        // CommentResource::withoutWrapping();
        JsonResource::withoutWrapping(); // Make JSON return without wrapped by 'data'
    }
}
