<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Comment extends Model
{
    protected $primaryKey = 'id_tag';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'material_tag', 'id_tag', 'id_material');
    }
}