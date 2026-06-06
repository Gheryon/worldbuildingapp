<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Imagen extends Model
{
  use HasFactory;

  protected $table = 'imagenes';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'path',
    'owner',
    'table_owner',
  ];

  /**
   * Almacena una nueva imagen en la base de datos.
   *
   * @param array $request
   * @return \App\Models\Imagen
   */
  public static function store_imagen(array $request)
  {
    return DB::transaction(function () use ($request) {
      $imagen = self::create($request);

      // Guardamos los cambios finales (rutas de imágenes y fechas)
      $imagen->save();

      return $imagen;
    });
  }
}
