<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Exception;

class articulo extends Model
{
  use HasFactory;

  protected $table = 'articulosgenericos';
  protected $primaryKey = 'id_articulo';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'contenido',
    'tipo',
  ];

  /**
   * Obtiene los articulos donde tipo != relato, ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_articulos($orden, $filtro)
  {
    try {
      if($filtro == 'all') {
        $articulos = self::where('tipo', '!=', 'relato')
          ->select('id_articulo', 'nombre', 'tipo')
          ->orderBy('nombre', $orden)
          ->get();
      } else {
        $articulos = self::where('tipo', '=', $filtro)
          ->select('id_articulo', 'nombre', 'tipo')
          ->orderBy('nombre', $orden)
          ->get();
      }
      if ($articulos->isEmpty()) {
        Log::warning('articulo->get_articulos: No se encontraron articulos con tipo = ' . $filtro . '.');
        $articulos = ['error' => ['error' => 'No hay articulos guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('articulo->get_articulos: Error al obtener articulos: ' . $e->getMessage());
      $articulos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $articulos;
  }

  /**
   * Obtiene los articulos donde tipo = relato, ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_relatos()
  {
    try {
      $articulos = self::where('tipo', '=', 'relato')
        ->select('id_articulo', 'nombre')
        ->orderBy('nombre', 'asc')
        ->get();
      if ($articulos->isEmpty()) {
        Log::warning('articulo->get_relatos: No se encontraron articulos con tipo = relato.');
        $articulos = ['error' => ['error' => 'No hay relatos guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('articulo->get_relatos: Error al obtener articulos: ' . $e->getMessage());
      $articulos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $articulos;
  }

  /**
   * Obtiene un articulo por su ID.
   *
   * @param int $id
   * @return \App\Models\articulo|null
   */
  public static function get_articulo($id)
  {
    try {
      $articulo = self::findorfail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      Log::error("Articulo no encontrada con ID: " . $id);
      $articulo = ['error' => ['error' => "articulo no encontrada con ID: " . $id]];
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error("Error de base de datos al obtener articulo con ID: " . $id . " - " . $excepcion->getMessage());
      $articulo = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      Log::error("Error general al obtener articulo con ID: " . $id . " - " . $excepcion->getMessage());
      $articulo = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $articulo;
  }
}
