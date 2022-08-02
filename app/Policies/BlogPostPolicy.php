<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * ===================================
 * POLICIES
 * ===================================
 * Policy seperti Controller, Kita menggunakan file ini untuk menggabungkan
 * semua peraturan yang berhubungan dengan BlogPost, seperti allow delete untuk admin, dll.
 *
 * php artisan make:Policy PolicyName --model=ModelName
 *
 * ===================================
 *
 * Dengan membuat flag --model, Laravel akan membuat policy dengan template yang sudah sesuai dengan
 * Model kita
 */
class BlogPostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BlogPost  $blogPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, BlogPost $blogPost)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BlogPost  $blogPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, BlogPost $blogPost)
    {
        // Memberikan akses update untuk owner post
        // dd('Updating!');
        return $user->id == $blogPost->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BlogPost  $blogPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, BlogPost $blogPost)
    {
        // Memberikan akses delete untuk owner post
        // dd("deleting!");
        return $user->id == $blogPost->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BlogPost  $blogPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, BlogPost $blogPost)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BlogPost  $blogPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, BlogPost $blogPost)
    {
        //
    }
}
