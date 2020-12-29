<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',     
        'chapo',
        'content',
        'user_id',
    ];

    /**
     * Get the comments for the blog post.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * Get the author of the blog post.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
