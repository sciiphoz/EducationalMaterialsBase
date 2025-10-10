<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Like extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_like';
    public $timestamps = false;

    protected $fillable = [
        'id_material',
        'id_user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}