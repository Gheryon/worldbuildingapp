<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Conflicto extends Model
{
  use HasFactory;

  protected $table = 'conflicto';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'descripcion',
    'preludio',
    'desarrollo',
    'resultado',
    'consecuencias',
    'otros',
    'id_tipo_conflicto',
    'tipo_localizacion',
    'id_conflicto_padre',
    'fecha_inicio',
    'fecha_fin',
  ];

  /**
   * Obtiene los conflictos ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_conflictos($tipo, $orden)
  {
    try {
      if ($tipo != 0) {
        $conflictos = self::leftjoin('tipo_conflicto', 'conflicto.id_tipo_conflicto', '=', 'tipo_conflicto.id')
          ->select('conflicto.id', 'conflicto.nombre', 'descripcion', 'tipo_conflicto.nombre AS tipo')
          ->where('conflicto.id_tipo_conflicto', '=', $tipo)
          ->orderBy('nombre', $orden)->get();
      } else {
        $conflictos = self::leftjoin('tipo_conflicto', 'conflicto.id_tipo_conflicto', '=', 'tipo_conflicto.id')
          ->select('conflicto.id', 'conflicto.nombre', 'descripcion', 'tipo_conflicto.nombre AS tipo')
          ->orderBy('nombre', $orden)->get();
      }
      if ($conflictos->isEmpty()) {
        Log::warning('No se encontraron conflictos.');
        $conflictos = ['error' => ['error' => 'No hay conflictos guardadas.']];
      }
    } catch (\Exception $e) {
      Log::error('Error al obtener conflictos: ' . $e->getMessage());
      $conflictos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $conflictos;
  }

  /**
   * Obtiene una conflicto por su ID.
   *
   * @param int $id
   * @return \App\Models\Conflicto|null
   */
  public static function get_conflicto($id)
  {
    try {
      $conflicto = self::findorfail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      Log::error("Conflicto no encontrado con ID: " . $id);
      $conflicto = ['error' => ['error' => "conflicto no encontrada con ID: " . $id]];
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error("Error de base de datos al obtener conflicto con ID: " . $id . " - " . $excepcion->getMessage());
      $conflicto = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error("Error general al obtener conflicto con ID: " . $id . " - " . $excepcion->getMessage());
      $conflicto = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $conflicto;
  }

  /**
   * Obtiene los id de las organizaciones presentes en un conflicto en un determinado bando.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_paises_bando($id, $lado)
  {
    try {
      $bando = DB::table('conflicto_beligerantes')->select('id_organizacion')->where('id_conflicto', '=', $id)->where('lado', '=', $lado)->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('Conflicto->get_bando: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $bando = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error('Conflicto->get_bando: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $bando = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $bando;
  }

  /**
   * Obtiene los id de los personajes presentes en un conflicto en un determinado bando.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_personajes_bando($id, $lado)
  {
    try {
      $bando = DB::table('conflicto_personajes')->select('id_personaje')->where('id_conflicto', '=', $id)->where('rol', '=', $lado)->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('Conflicto->get_bando: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $bando = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error('Conflicto->get_bando: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $bando = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $bando;
  }
}
