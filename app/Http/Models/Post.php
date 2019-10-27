<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function postable(){
        return $this->morphTo();
    }

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function post_translations(){
        return $this->hasMany(PostTranslation::class);
    }

    public function taggings(){
        return $this->hasMany(Tagging::class);
    }

    public function tags(){
        return $this->hasManyThrough(Tag::class, Tagging::class, 'tag_id', 'id');
    }

    public function scopeWithPostTranslation($query, $locale=null){
        $subs  = PostTranslation::withLocale($locale);
        $posts = $query->joinSub($subs, 'post_translations', function($join){ $join->on('posts.id', '=', 'post_translations.post_id'); })
                ->select('posts.*', 'post_translations.title', 'post_translations.description', 'post_translations.content', 'post_translations.locale', 'post_translations.status', 'post_translations.count_submit');
        return $posts;
    }
}
