<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'content'];

    public function blogPost(){
        return $this->belongsTo('App\Models\BlogPost');
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
            // Menghapus cache untuk post ketika post dilakukan update
            Cache::tags(['blog-post'])->forget("blog-post-{$comment->blog_post_id}");
            Cache::tags(['blog-post'])->forget("mostCommented");
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
