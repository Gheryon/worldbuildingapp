<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conflicto extends Model
{
  use HasFactory;

  protected $table = 'conflicto';
  protected $primaryKey = 'id';
  public $timestamps=false;

  protected $fillable = [
    'nombre',
    'descripcion',
    'preludio',
    'desarrollo',
    'resultado',
    'consecuencias',
    'otros',
    'id_tipo_conflicto',
    'tipo_localizacion',
    'id_conflicto_padre',
    'fecha_inicio',
    'fecha_fin',
  ];
}
