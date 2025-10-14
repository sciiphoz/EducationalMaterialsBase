<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['text', 'user_id', 'material_id'];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }
}