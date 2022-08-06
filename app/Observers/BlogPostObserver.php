<?php

namespace App\Observers;

use App\Models\BlogPost;
use Illuminate\Support\Facades\Cache;

/**
 * Observer: Menjadi pengganti observer
 */
class BlogPostObserver
{
    public function deleting(BlogPost $blogPost)
    {
        // Ketika blogpost di delete, maka fungsi ini akan men-delete semua data comments yang berhubungan dengan blog post tersebut.
        $blogPost->comments()->delete();
        $blogPost->image()->delete();
        Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
    }

    public function updating(BlogPost $blogPost)
    {
        // Menghapus cache untuk post ketika post dilakukan update
        Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
    }

    public function restoring(BlogPost $blogPost)
    {
        $blogPost->comments()->restore();
    }
}
