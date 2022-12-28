<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tr_followers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'follower_id', 'status', 'created_by', 'updated_by', 'deleted_by'
    ];

    public function followers()
    {
        return $this->belongsTo(User::class, 'follower_id', 'id');
    }

    public function following()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
