<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Using Gate
        // Gate dapat mendefine untuk beberapa peraturan.
        Gate::define('update-post', function($user, $post){
            // Mengecek apakah user dapat mengedit post
            return $user->id == $post->user_id;
        });

        // Gate untuk delete post
        Gate::define('delete-post', function($user, $post){
            // Mengecek apakah user dapat mengedit post
            return $user->id == $post->user_id;
        });
    }
}
