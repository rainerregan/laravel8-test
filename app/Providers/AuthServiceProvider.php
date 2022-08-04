<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\BlogPostPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     * @see BlogPostPolicy
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\BlogPost' => 'App\Policies\BlogPostPolicy',
        User::class => UserPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('home.secret', function($user) {
            return $user->is_admin;
        });

        // Gate untuk admin only, bisa update dan delete
        Gate::before(function ($user, $ability){
            if ($user->is_admin && in_array($ability, ['update', 'delete'])) {
                return true; // Return true, jika user merupakan admin.
            }
        });


        // Using Gate
        // Gate dapat mendefine untuk beberapa peraturan.
        // Gate::define('update-post', function($user, $post){
        //     // Mengecek apakah user dapat mengedit post
        //     return $user->id == $post->user_id;
        // });

        // Gate untuk delete post
        // Gate::define('delete-post', function($user, $post){
        //     // Mengecek apakah user dapat mengedit post
        //     return $user->id == $post->user_id;
        // });

        // ================
        // Override Before
        // ================
        // Before akan jalan sebelum mengecek Gate lainnya
        // Jika return adalah true, maka user dapat mem by-pass semua Gate diatas
        // Jika return adalah selain true, maka Laravel akan menggunakan define diatas untuk mengecek status.
        // Parameter user adalah parameter yang di provide oleh laravel
        // Parameter ability adalah action yang akan kita cek, seperti 'update-post'
        # Gate dibawah berfungsi untuk memberikan ability bagi admin
        // Gate::before(function ($user, $ability){
        //     if ($user->is_admin && in_array($ability, ['update', 'delete'])) {
        //         return true; // Return true, jika user merupakan admin.
        //     }
        // });

        // After dipanggil untuk mengecek setelah dilakukan pengecekan lainnya
        // Gate::after(function($user, $ability, $result) {
        //     if($user->is_admin){
        //         return true;
        //     }
        // });

        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        // ===========
        // Use Policy
        // ===========
        // Menggunakan policy untuk define rules
        // Policy berlaku seperti controller, namun untuk rules
        // Policy berfokus untuk mensentralisasi logic untuk rules pada controller.
        // Gate::define('posts.update', [BlogPostPolicy::class, 'update']);
        // Gate::define('posts.delete', [BlogPostPolicy::class, 'delete']);

        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        // Menggunakan Resource
        // Menggunakan resource adalah cara lain untuk menggunakan define policy
        // Berfungsi seperti resource pada controller
        // Jadi kita tidak perlu untuk mendefine 1 per 1
        // Akan meng-control untuk posts.create, posts.view, posts.update, posts.delete
        // Note: Ini akan berfungsi jika kita tidak mengubah ability name pada policy
        // Gate::resource('posts', BlogPostPolicy::class);

        // Update: Untuk mensetting policy mana yang dipakai untuk model yang mana, kita dapat menggunakan
        // array protected $policies pada awal class diatas.
        // Jadi kita tidak perlu untuk menggunakan Gate untuk mendeskripsikan secara eksplisit

    }
}
