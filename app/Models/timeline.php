<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class timeline extends Model
{
    use HasFactory;

    protected $table = 'timelines';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable = [
        'dia',
        'mes',
        'anno',
        'nombre',
        'descripcion',
        'id_linea_temporal',
        'id_tipo_evento'
    ];
}
