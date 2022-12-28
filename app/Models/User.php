<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'first_name',
        'last_name',
        'phone_number',
        'date_of_birth',
        'profile_pic',
        'path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the images for the post.
     *
     * Syntax: return $this->hasMany(Comment::class, 'foreign_key', 'local_key');
     *
     * Example: return $this->hasMany(Comment::class, 'post_id', 'id');
     *
     */
    public function followers()
    {
        return $this->hasMany(Follower::class, 'follower_id', 'id');
    }

    public function following()
    {
        return $this->hasMany(Follower::class, 'user_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(PostLikes::class, 'user_id', 'id');
    }
}
