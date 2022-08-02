<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

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
    }
}
