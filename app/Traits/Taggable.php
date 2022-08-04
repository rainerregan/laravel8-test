<?php

namespace App\Traits;

use App\Models\Tag;

trait Taggable
{

    protected static function bootTaggable()
    {
        static::updating(function ($model) {
            // Mencari tags dalam content dan langsung meng-assignnya kedalam blogpost
            $model->tags()->sync(static::findTagsInContent($model->content));
        });

        static::created(function ($model) {
            // Mencari tags dalam content dan langsung meng-assignnya kedalam blogpost
            $model->tags()->sync(static::findTagsInContent($model->content));
        });
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    }

    private static function findTagsInContent($content)
    {
        // @TagName@
        preg_match_all('/@([^@]+)@/m', $content, $tags);

        return Tag::whereIn('name', $tags[1] ?? [])->get();
    }
}
