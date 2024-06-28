<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipo_organizacion extends Model
{
    use HasFactory;
    protected $table = 'tipo_organizacion';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable = [
        'nombre',
    ];
}
