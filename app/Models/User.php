<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $fillable = [
        'login',
        'email',
        'password',
        'id_role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'id_user');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'id_user');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'id_user');
    }

    public function isAdmin(): bool
    {
        return $this->role->name === 'admin';
    }
}