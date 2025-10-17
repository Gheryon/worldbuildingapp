<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class organizacion extends Model
{
  use HasFactory;

  protected $table = 'organizaciones';
  protected $primaryKey = 'id_organizacion';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'gentilicio',
    'capital',
    'escudo',
    'descripcionBreve',
    'lema',
    'demografia',
    'historia',
    'estructura',
    'politicaExteriorInterior',
    'militar',
    'religion',
    'cultura',
    'educacion',
    'tecnologia',
    'territorio',
    'economia',
    'recursosNaturales',
    'otros',
    'id_ruler',
    'id_owner',
    'id_tipo_organizacion',
    'fundacion',
    'disolucion',
  ];

  /**
   * Obtiene las organizaciones con id diferente de 0, ordenadas por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_organizaciones($tipo, $orden)
  {
    try {
      if($tipo!=0){
        $paises = self::join('tipo_organizacion', 'organizaciones.id_tipo_organizacion', '=', 'tipo_organizacion.id')
          ->select('organizaciones.id_organizacion', 'organizaciones.nombre', 'organizaciones.escudo', 'tipo_organizacion.nombre AS tipo')
          ->where('organizaciones.id_organizacion', '!=', 0)
          ->where('organizaciones.id_tipo_organizacion', '=', $tipo)
          ->orderBy('organizaciones.nombre', $orden)->get();;
      }else{
        $paises = self::join('tipo_organizacion', 'organizaciones.id_tipo_organizacion', '=', 'tipo_organizacion.id')
          ->select('organizaciones.id_organizacion', 'organizaciones.nombre', 'organizaciones.escudo', 'tipo_organizacion.nombre AS tipo')
          ->where('organizaciones.id_organizacion', '!=', 0)
          ->orderBy('organizaciones.nombre', $orden)->get();;
      }

      if ($paises->isEmpty()) {
        Log::warning('organizacion->get_organizaciones: No se encontraron paises con id diferente de 0.');
        $paises = ['error' => ['error' => 'No hay paises guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('organizacion->get_organizaciones: Error al obtener paises: ' . $e->getMessage());
      $paises = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $paises;
  }
  
  /**
   * Obtiene id y nombre de las organizaciones con id diferente de 0, ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_organizaciones_id_nombre()
  {
    try {
      $organizaciones=self::select('id_organizacion', 'nombre')
          ->where('organizaciones.id_organizacion', '!=', 0)
          ->orderBy('organizaciones.nombre', 'asc')->get();

      if ($organizaciones->isEmpty()) {
        Log::warning('organizacion->get_organizaciones_id_nombre: No se encontraron organizaciones con id diferente de 0.');
        $organizaciones = ['error' => ['error' => 'No hay organizaciones guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('organizacion->get_organizaciones_id_nombre: Error al obtener organizaciones: ' . $e->getMessage());
      $organizaciones = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $organizaciones;
  }

  /**
   * Obtiene los id de las religiones presentes en una organizacion en un determinado bando.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_religiones_presentes($id)
  {
    try {
      $religiones = DB::table('religion_presence')->select('religion')->where('organizacion', '=', $id)->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('Organizacion->get_religiones_presentes: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $religiones = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error('Organizacion->get_religiones_presentes: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $religiones = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $religiones;
  }

  /**
   * Obtiene una organización por su ID.
   *
   * @param int $id
   * @return \App\Models\organizacion|null
   */
  public static function get_organizacion($id)
  {
    try {
      $organizacion = self::findorfail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      Log::error("organizacion->get_organizacion: Organización no encontrada con ID: " . $id);
      $organizacion = ['error' => ['error' => "Organización no encontrada con ID: " . $id]];
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error("organizacion->get_organizacion: Error de base de datos al obtener organización con ID: " . $id . " - " . $excepcion->getMessage());
      $organizacion = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error("organizacion->get_organizacion: Error general al obtener organización con ID: " . $id . " - " . $excepcion->getMessage());
      $organizacion = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $organizacion;
  }
}
