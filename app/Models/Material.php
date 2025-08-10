<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $primaryKey = 'id_material';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_material',
        'name',
        'text',
        'date',
        'rating',
        'isPrivate',
        'id_user'
    ];

    protected $casts = [
        'isPrivate' => 'boolean',
        'date' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'id_material');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'id_material');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'material_tag', 'id_material', 'id_tag');
    }
}