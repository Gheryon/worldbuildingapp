<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class tipo_organizacion extends Model
{
  use HasFactory;
  protected $table = 'tipo_organizacion';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
  ];

  /* Obtiene los tipos de organizaciones ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_tipos_organizaciones()
  {
    try {
      // Intenta ejecutar la consulta y devolver el resultado ordenado.
      return self::orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        'Error de base de datos al obtener los tipos de organización.',
        [
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      // Devuelve una colección vacía para que la aplicación pueda continuar
      return new Collection();
    } catch (\Exception $e) {
      Log::error(
        'Error inesperado al obtener los tipos de organización.',
        [
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      //Devuelve una colección vacía como medida de seguridad.
      return new Collection();
    }
  }
}
