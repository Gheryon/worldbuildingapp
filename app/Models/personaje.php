<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class personaje extends Model
{
  use HasFactory;

  protected $table = 'personaje';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'Nombre',
    'nombreFamilia',
    'Apellidos',
    'lugar_nacimiento',
    'nacimiento',
    'fallecimiento',
    'causa_fallecimiento',
    'Descripcion',
    'DescripcionShort',
    'Personalidad',
    'salud',
    'Deseos',
    'Miedos',
    'Magia',
    'educacion',
    'Historia',
    'Religion',
    'Familia',
    'Politica',
    'Retrato',
    'id_foranea_especie',
    'sexo',
    'otros'
  ];

  /**
   * Obtiene los personajes con id diferente de 0, ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_personajes($orden = 'asc', $tipo = '0')
  {
    try {
      if($tipo!=0){
        $personajes=self::leftjoin('especies', 'personaje.id_foranea_especie', '=', 'especies.id')
          ->select('personaje.id', 'personaje.Nombre', 'Retrato', 'Sexo', 'id_foranea_especie', 'especies.nombre AS especie')
          ->where('personaje.id', '!=', 0)
          ->where('personaje.id_foranea_especie', '=', $tipo)
          ->orderBy('personaje.Nombre', $orden)->get();
      }else{
        $personajes=self::leftjoin('especies', 'personaje.id_foranea_especie', '=', 'especies.id')
          ->select('personaje.id', 'personaje.Nombre', 'Retrato', 'Sexo', 'id_foranea_especie', 'especies.nombre AS especie')
          ->where('personaje.id', '!=', 0)
          ->orderBy('personaje.Nombre', $orden)->get();
      }

      if ($personajes->isEmpty()) {
        Log::warning('personaje->get_personajes: No se encontraron personajes con id diferente de 0.');
        $personajes = ['error' => ['error' => 'No hay personajes guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('personaje->get_personajes: Error al obtener personajes: ' . $e->getMessage());
      $personajes = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $personajes;
  }

  /**
   * Obtiene id y nombre de los personajes con id diferente de 0, ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_personajes_id_nombre()
  {
    try {
      $personajes=self::select('id', 'Nombre')
          ->where('personaje.id', '!=', 0)
          ->orderBy('personaje.Nombre', 'asc')->get();

      if ($personajes->isEmpty()) {
        Log::warning('personaje->get_personajes: No se encontraron personajes con id diferente de 0.');
        $personajes = ['error' => ['error' => 'No hay personajes guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('personaje->get_personajes: Error al obtener personajes: ' . $e->getMessage());
      $personajes = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $personajes;
  }

  /**
   * Obtiene un personaje por su ID.
   *
   * @param int $id
   * @return \App\Models\personaje|null
   */
  public static function get_personaje($id)
  {
    try {
      $personaje = self::findorfail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      Log::error("personaje->get_personaje: Personaje no encontrado con ID: " . $id);
      $personaje = ['error' => ['error' => "personaje no encontrada con ID: " . $id]];
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error("personaje->get_personaje: Error de base de datos al obtener personaje con ID: " . $id . " - " . $excepcion->getMessage());
      $personaje = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error("personaje->get_personaje: Error general al obtener personaje con ID: " . $id . " - " . $excepcion->getMessage());
      $personaje = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $personaje;
  }
}
