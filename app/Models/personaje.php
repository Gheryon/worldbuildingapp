<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personaje extends Model
{
    use HasFactory;

    protected $table = 'personaje';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable = [
        'Nombre',
        'nombreFamilia',
        'Apellidos',
        'lugar_nacimiento',
        'nacimiento',
        'fallecimiento',
        'causa_fallecimiento',
        'Descripcion',
        'DescripcionShort',
        'Personalidad',
        'Deseos',
        'Miedos',
        'Magia',
        'educacion',
        'Historia',
        'Religion',
        'Familia',
        'Politica',
        'Retrato',
        'id_foranea_especie',
        'sexo',
        'otros'
    ];
}
