<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;

/*
 |==================================================================
 | Eloquent Model: BlogPost
 |==================================================================
 | Satu model eloquent merepresentasikan satu table di database kita.
 | Di dalam model, kita bisa menentukan relationships dan lainnya.
 |==================================================================
 */
class BlogPost extends Model
{
    use HasFactory;

    // Import Dependecy untuk menggunakan Soft Delete
    use SoftDeletes;

    // Fillable berfungsi untuk menentukan field apa saja yang boleh untuk
    // dimodifikasi melalui mass assignment.
    protected $fillable = ['title', 'content', 'user_id'];

    // One-to-Many relationship eloquent model di Laravel
    // Satu blog post bisa memiliki banyak comments
    public function comments(){
        // Local query scope
        return $this->hasMany('App\Models\Comment')->latest();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    // Local Query Scope
    // Local query scope menggantikan global query scope yang memiliki issues
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {

        // withCount akan membuat column baru bernama comments_count
        // Kita dapat mengurutkan most commented post dengan ini
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    // Events
    // Events adalah method yang dipanggil ketika suatu event terjadi seperti
    // deleting, updating, dll.
    public static function boot(){
        // Give admin to see all deleted posts
        static::addGlobalScope(new DeletedAdminScope);


        parent::boot();

        // Menggunakan Global Query scope untuk mempermudah
        // Query secara global.
        // Global scope menambahkan query yang telah dibuat di scope.
        // static::addGlobalScope(new LatestScope);

        // Fungsi ini akan dijalankan ketika suatu data di delete.
        // Dilajankan ketika suatu blog post di delete.
        // Fungsi Delete ini akan mendelete secara permanent dari database.
        // static::deleting(function(BlogPost $blogPost){
        //     // Ketika blogpost di delete, maka fungsi ini akan men-delete semua data comments yang berhubungan dengan
        //     // blog post tersebut.
        //     $blogPost->comments()->delete();
        // });

        // Update: Events sudah digantikan dengan fitur SQL CASCADE langsung di database.

        // Update: Dengan menambahkan softdelete, fitur dibawah ini tidak akan mendelete secara permanent lagi.
        static::deleting(function(BlogPost $blogPost){
                // Ketika blogpost di delete, maka fungsi ini akan men-delete semua data comments yang berhubungan dengan
                // blog post tersebut.
                $blogPost->comments()->delete();
            });

        // Subscribing ke event restoring untuk restore data.
        static::restoring(function(BlogPost $blogPost){
            $blogPost->comments()->restore();
        });

    }
}
