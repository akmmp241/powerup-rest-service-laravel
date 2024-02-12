<?php

namespace App\Models;

use App\Traits\UserUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UserUuid;

    protected $table = "users";

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'token',
        'verification_token',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'token',
        'verification_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
