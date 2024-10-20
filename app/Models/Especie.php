<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
  use HasFactory;

  protected $table = 'especies';
  protected $primaryKey = 'id';
  public $timestamps=false;

  protected $fillable = [
    'nombre',
    'edad',
    'peso',
    'altura',
    'longitud',
    'estatus',
    'anatomia',
    'alimentacion',
    'reproduccion',
    'distribucion',
    'habilidades',
    'domesticacion',
    'explotacion',
    'otros',
  ];
}
