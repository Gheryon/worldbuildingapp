<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

  /**
   * Obtiene las construcciones ordenadas por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_construcciones($tipo, $orden)
  {
    try {
      if ($tipo != 0) {
        $construcciones = self::leftjoin('tipo_construccion', 'construccions.tipo', '=', 'tipo_construccion.id')
          ->select('construccions.id', 'construccions.nombre', 'tipo_construccion.nombre AS tipo')
          ->where('construccions.tipo', '=', $tipo)
          ->orderBy('construccions.nombre', $orden)->get();
      } else {
        $construcciones = self::leftjoin('tipo_construccion', 'construccions.tipo', '=', 'tipo_construccion.id')
          ->select('construccions.id', 'construccions.nombre', 'tipo_construccion.nombre AS tipo')
          ->orderBy('construccions.nombre', $orden)->get();
      }
      if ($construcciones->isEmpty()) {
        Log::warning('No se encontraron construcciones.');
        $construcciones = ['error' => ['error' => 'No hay construcciones guardadas.']];
      }
    } catch (\Exception $e) {
      Log::error('Error al obtener construcciones: ' . $e->getMessage());
      $construcciones = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $construcciones;
  }

  /**
   * Obtiene una construccion por su ID.
   *
   * @param int $id
   * @return \App\Models\Construccion|null
   */
  public static function get_construccion($id)
  {
    try {
      $construccion = self::findorfail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      Log::error("Construccion no encontrado con ID: " . $id);
      $construccion = ['error' => ['error' => "Construccion no encontrada con ID: " . $id]];
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error("Error de base de datos al obtener construccion con ID: " . $id . " - " . $excepcion->getMessage());
      $construccion = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error("Error general al obtener construccion con ID: " . $id . " - " . $excepcion->getMessage());
      $construccion = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $construccion;
  }
}
