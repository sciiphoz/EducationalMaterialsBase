<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Material extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'text', 'date', 'isPrivate', 'isDisabled', 'user_id', 'tag_id'];

    protected $casts = [
        'isPrivate' => 'boolean',
        'isDisabled' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function like()
    {
        return $this->hasMany(Like::class);
    }

    public function scopePublic($query)
    {
        return $query->where('isPrivate', false);
    }

    // Scope для материалов с разрешенными комментариями
    public function scopeWithLikesSum($query)
    {
        return $query->withCount(['like as likes_sum' => function($query) {
            $query->select(DB::raw('COALESCE(SUM(value), 0)'));
        }]);
    }
}