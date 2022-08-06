<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use App\Models\BlogPost;
use App\Models\Tag;
use App\Traits\Taggable;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Taggable;

    protected $fillable = ['user_id', 'content'];

    protected $hidden = ['deleted_at', 'commentable_type', 'commentable_id', 'user_id'];

    public function commentable()
    {
        return $this->morphTo();
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

    // Events
    // Events adalah method yang dipanggil ketika suatu event terjadi seperti
    // deleting, updating, dll.
    public static function boot(){
        parent::boot();

        // Handling events akan dilakukan menggunakan observer
        // @see CommentObserver
    }

}
