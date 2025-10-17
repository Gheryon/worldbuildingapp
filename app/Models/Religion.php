<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Religion extends Model
{
  use HasFactory;

  protected $table = 'religiones';
  protected $primaryKey = 'id';
  public $timestamps=false;

  protected $fillable = [
    'nombre',
    'lema',
    'descripcion',
    'historia',
    'cosmologia',
    'doctrina',
    'sagrado',
    'fiestas',
    'politica',
    'estructura',
    'sectas',
    'otros',
    'fundacion',
    'disolucion',
];
/**
   * Obtiene las religiones almacenadas, ordenadas por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_religiones()
  {
    try {
      $religiones=self::select('id', 'nombre')
          ->orderBy('nombre', 'asc')->get();

      if ($religiones->isEmpty()) {
        Log::warning('religion->get_religiones: No se encontraron religiones con id diferente de 0.');
        $religiones = ['error' => ['error' => 'No hay religiones guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('religion->get_religiones: Error al obtener religiones: ' . $e->getMessage());
      $religiones = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $religiones;
  }

  /**
   * Obtiene una religion por su ID.
   *
   * @param int $id
   * @return \App\Models\Religion|null
   */
  public static function get_religion($id)
  {
    try {
      $religion = self::findorfail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      Log::error("religion no encontrada con ID: " . $id);
      $religion = ['error' => ['error' => "religion no encontrada con ID: " . $id]];
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error("religion->get_religion: Error de base de datos al obtener religion con ID: " . $id . " - " . $excepcion->getMessage());
      $religion = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error("religion->get_religion: Error al obtener religion con ID: " . $id . " - " . $excepcion->getMessage());
      $religion = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $religion;
  }
}
