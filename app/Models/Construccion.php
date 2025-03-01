<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construccion extends Model
{
  use HasFactory;

  protected $table = 'construccions';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'descripcion',
    'historia',
    'proposito',
    'aspecto',
    'otros',
    'tipo',
    'ubicacion',
    'construccion',
    'destruccion',
  ];
}
