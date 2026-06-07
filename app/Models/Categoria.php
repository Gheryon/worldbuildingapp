<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
  use HasFactory;

  protected $fillable = [
    'nombre',
  ];

  /**
   * Las imágenes que pertenecen a esta categoría.
   */
  public function imagenes()
  {
    return $this->hasMany(Imagen::class);
  }
}
