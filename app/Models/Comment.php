<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use App\Models\BlogPost;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'content'];

    public function commentable()
    {
        return $this->morphTo();
    }

    // Local Query Scope
    // Local query scope menggantikan global query scope yang memiliki issues
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    // Events
    // Events adalah method yang dipanggil ketika suatu event terjadi seperti
    // deleting, updating, dll.
    public static function boot(){
        parent::boot();

        // static::addGlobalScope(new LatestScope);
        // Event untuk updating
        static::creating(function (Comment $comment) {
            if($comment->commentable_type === BlogPost::class){
                // Menghapus cache untuk post ketika post dilakukan update
                Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
                Cache::tags(['blog-post'])->forget("mostCommented");
            }
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
