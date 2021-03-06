<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function leftChild()
    {
        return $this->belongsTo(User::class, 'left_user', 'id');
    }

    public function rightChild()
    {
        return $this->belongsTo(User::class, 'right_user', 'id');
    }

    public function childs()
    {
        return $this->hasMany(User::class, 'parent_user', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_user', 'id');
    }
}
