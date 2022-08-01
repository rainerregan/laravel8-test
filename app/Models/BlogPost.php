<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

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
    protected $fillable = ['title', 'content'];

    // One-to-Many relationship eloquent model di Laravel
    // Satu blog post bisa memiliki banyak comments
    public function comments(){
        return $this->hasMany('App\Models\Comment');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    // Events
    // Events adalah method yang dipanggil ketika suatu event terjadi seperti
    // deleting, updating, dll.
    public static function boot(){
        parent::boot();

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
