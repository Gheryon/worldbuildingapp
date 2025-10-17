<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Exception;

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

  /**
   * Obtiene los asentamientos con id diferente de 0, ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_asentamientos()
  {
    try {
      $asentamientos = self::where('id', '!=', 0)
        ->select('id', 'nombre')
        ->orderBy('nombre', 'asc')
        ->get();
      if ($asentamientos->isEmpty()) {
        Log::warning('No se encontraron asentamientos con id diferente de 0.');
        $asentamientos = ['error' => ['error' => 'No hay asentamientos guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('Error al obtener asentamientos: ' . $e->getMessage());
      $asentamientos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $asentamientos;
  }

  /**
   * Obtiene una organización por su ID.
   *
   * @param int $id
   * @return \App\Models\Asentamiento|null
   */
  public static function get_asentamiento($id)
  {
    try {
      $organizacion = self::findorfail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      Log::error("Organización no encontrada con ID: " . $id);
      $organizacion = ['error' => ['error' => "Organización no encontrada con ID: " . $id]];
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error("Error de base de datos al obtener organización con ID: " . $id . " - " . $excepcion->getMessage());
      $organizacion = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      Log::error("Error general al obtener organización con ID: " . $id . " - " . $excepcion->getMessage());
      $organizacion = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $organizacion;
  }
}
