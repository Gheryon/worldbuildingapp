<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Exception;

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

  /**
   * Obtiene las especies ordenadas por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_especies()
  {
    try {
      $especies = self::select('id', 'nombre')->orderBy('nombre', 'asc')->get();

      if ($especies->isEmpty()) {
        Log::warning('No se encontraron especies.');
        $especies = ['error' => ['error' => 'No hay especies guardadas.']];
      }

    } catch (\Exception $e) {
      Log::error('Error al obtener especies: ' . $e->getMessage());
      $especies = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $especies;
  }

  /**
   * Obtiene una especie por su ID.
   *
   * @param int $id
   * @return \App\Models\Especie|null
   */
  public static function get_especie($id)
  {
    try {
      $especie = self::findorfail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      Log::error("Especie no encontrada con ID: " . $id);
      $especie = ['error' => ['error' => "especie no encontrada con ID: " . $id]];
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error("Especie->get_especie: Error de base de datos al obtener especie con ID: " . $id . " - " . $excepcion->getMessage());
      $especie = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error("Especie->get_especie: Error al obtener especie con ID: " . $id . " - " . $excepcion->getMessage());
      $especie = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $especie;
  }
}
