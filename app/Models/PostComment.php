<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class PostComment extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tr_posts_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'user_id', 'comments', 'comment_parent_id', 'created_by', 'updated_by', 'deleted_by'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recursivePosts()
    {
        return $this->hasManyOfDescendantsAndSelf(Post::class);
    }

    public function getParentKeyName()
    {
        return 'comment_parent_id';
    }
}
