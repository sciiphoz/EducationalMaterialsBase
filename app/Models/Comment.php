<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_tag';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function material()
    {
        return $this->belongsToMany(Material::class);
    }
}