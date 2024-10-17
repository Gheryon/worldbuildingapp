<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
  use HasFactory;

  protected $table = 'religiones';
  protected $primaryKey = 'id';
  public $timestamps=false;

  protected $fillable = [
    'nombre',
    'lema',
    'descripcion',
    'historia',
    'cosmologia',
    'doctrina',
    'sagrado',
    'fiestas',
    'politica',
    'estructura',
    'sectas',
    'otros',
    'fundacion',
    'disolucion',
];
}
