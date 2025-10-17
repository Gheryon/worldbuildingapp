<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Exception;

class Lugar extends Model
{
  use HasFactory;

  protected $table = 'lugares';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'descripcion_breve',
    'otros_nombres',
    'geografia',
    'ecosistema',
    'clima',
    'flora_fauna',
    'recursos',
    'historia',
    'otros',
    'id_owner',
    'id_tipo_lugar',
  ];
  /**
   * Obtiene los lugares ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_lugares($tipo, $orden)
  {
    try {
      if ($tipo == 0) {
        $lugares = self::leftjoin('tipo_lugar', 'lugares.id_tipo_lugar', '=', 'tipo_lugar.id')
          ->select('lugares.id', 'lugares.nombre', 'descripcion_breve', 'tipo_lugar.nombre AS tipo')
          ->orderBy('lugares.nombre', $orden)->get();
      } else {
        $lugares = self::leftjoin('tipo_lugar', 'lugares.id_tipo_lugar', '=', 'tipo_lugar.id')
          ->select('lugares.id', 'lugares.nombre', 'descripcion_breve', 'tipo_lugar.nombre AS tipo')
          ->where('lugares.id_tipo_lugar', '=', $tipo)
          ->orderBy('lugares.nombre', $orden)->get();
      }
      if ($lugares->isEmpty()) {
        Log::warning('No se encontraron lugares.');
        $lugares = ['error' => ['error' => 'No hay lugares guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('Error al obtener lugares: ' . $e->getMessage());
      $lugares = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $lugares;
  }

  /**
   * Obtiene un lugar por su ID.
   *
   * @param int $id
   * @return \App\Models\Lugar|null
   */
  public static function get_lugar($id)
  {
    try {
      $lugar = self::findorfail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      Log::error("Lugar no encontrado con ID: " . $id);
      $lugar = ['error' => ['error' => "Lugar no encontrada con ID: " . $id]];
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error("Error de base de datos al obtener lugar con ID: " . $id . " - " . $excepcion->getMessage());
      $lugar = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      Log::error("Error general al obtener lugar con ID: " . $id . " - " . $excepcion->getMessage());
      $lugar = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $lugar;
  }
}
