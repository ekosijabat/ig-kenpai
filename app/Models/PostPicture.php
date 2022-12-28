<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostPicture extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tr_posts_pictures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'picture', 'path', 'created_by', 'updated_by', 'deleted_by'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
