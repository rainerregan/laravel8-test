<?php

namespace App\Http\ViewComposers;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer
{
    public function compose(View $view)
    {
        // Caching
        // Fungsi dibawah adalah untuk mendapatkan data dari cache dalam waktu tertentu
        // Jika tidak ada, maka kita akan membuat data tersebut di cache
        $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-commented', now()->addSeconds(10), function(){
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActive = Cache::remember('users-most-active', now()->addSeconds(10), function(){
            return User::withMostBlogPosts()->take(5)->get();
        });

        $mostActiveLastMonth = Cache::remember('users-most-active-last-month', now()->addSeconds(10), function(){
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });

        $view->with('mostCommented', $mostCommented);
        $view->with('mostActive', $mostActive);
        $view->with('mostActiveLastMonth', $mostActiveLastMonth);
    }
}
