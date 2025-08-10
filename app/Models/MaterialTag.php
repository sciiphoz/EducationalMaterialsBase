<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialTag extends Model
{
    protected $primaryKey = 'id_material_tag';
    public $timestamps = false;
    protected $table = 'material_tag';

    protected $fillable = [
        'id_material',
        'id_tag'
    ];
}