<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asentamiento extends Model
{
  use HasFactory;

  protected $table = 'asentamientos';
  protected $primaryKey = 'id';
  public $timestamps=false;

  protected $fillable = [
    'nombre',
    'gentilicio',
    'descripcion',
    'poblacion',
    'demografia',
    'gobierno',
    'infraestructura',
    'historia',
    'defensas',
    'economia',
    'cultura',
    'geografia',
    'clima',
    'recursos',
    'id_tipo_asentamiento',
    'fundacion',
    'disolucion',
    'id_owner',
    'otros',
  ];
}
