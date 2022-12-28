<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tm_posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'caption', 'total_likes', 'status', 'created_by', 'updated_by', 'deleted_by'
    ];

    /**
     * Get the images for the post.
     *
     * Syntax: return $this->hasMany(Comment::class, 'foreign_key', 'local_key');
     *
     * Example: return $this->hasMany(Comment::class, 'post_id', 'id');
     *
     */
    public function post_pictures()
    {
        return $this->hasMany(PostPicture::class, 'post_id', 'id');
    }

    public function post_likes()
    {
        return $this->hasMany(PostLikes::class, 'post_id', 'id');
    }

    public function post_comments()
    {
        return $this->hasMany(PostComment::class, 'post_id', 'id');
    }
}
