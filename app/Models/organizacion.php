<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class organizacion extends Model
{
    use HasFactory;

    protected $table = 'organizaciones';
    protected $primaryKey = 'id_organizacion';
    public $timestamps=false;

    protected $fillable = [
        'nombre',
        'gentilicio',
        'capital',
        'escudo',
        'descripcionBreve',
        'lema',
        'demografia',
        'historia',
        'estructura',
        'politicaExteriorInterior',
        'militar',
        'religion',
        'cultura',
        'educacion',
        'tecnologia',
        'territorio',
        'economia',
        'recursosNaturales',
        'otros',
        'id_ruler',
        'id_owner',
        'id_tipo_organizacion',
        'fundacion',
        'disolucion',
    ];
}
