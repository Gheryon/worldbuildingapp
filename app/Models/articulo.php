<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\ImageService;
use Exception;

class articulo extends Model
{
  use HasFactory;

  protected $table = 'articulos_genericos';
  protected $primaryKey = 'id';
  public $timestamps = true;

  protected $fillable = [
    'nombre',
    'contenido',
    'tipo',
  ];

  /**
   * Scope para filtrar y ordenar articulos.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->select(
      'articulos_genericos.id',
      'articulos_genericos.nombre',
      'articulos_genericos.tipo',
      'articulos_genericos.updated_at'
    )
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('articulos_genericos.nombre', 'LIKE', "%{$search}%");
      })
      ->when($filtros['tipo'] ?? null, function ($q, $tipo) {
        if ($tipo !== 'all') $q->where('articulos_genericos.tipo', $tipo);
      })
      // Prioridad de ordenación
      ->when($filtros['fecha'] ?? null, function ($q, $fecha) {
        // Si el usuario eligió un orden por fecha, se aplica este
        $q->orderBy('articulos_genericos.updated_at', $fecha);
      }, function ($q) use ($filtros) {
        // Si NO hay filtro de fecha, ordenamos por nombre por defecto
        $q->orderBy('articulos_genericos.nombre', $filtros['orden'] ?? 'asc');
      });
  }

  /**
   * Almacena un nuevo articulo en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\articulo
   */
  public static function store_articulo($request)
  {
    return DB::transaction(function () use ($request) {
      $articulo = self::create([
        'nombre' => $request->nombre,
        'tipo' => $request->tipo
      ]);

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      $articulo->contenido = $imageService->processSummernoteImages(
        $request->contenido,
        "articulos",
        $articulo->id
      );

      $articulo->save();

      return $articulo;
    });
  }

  /**
   * Actualiza un articulo existente en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\articulo
   */
  public function update_articulo($request)
  {
    return DB::transaction(function () use ($request) {
      $this->fill([
        'nombre' => $request->nombre,
        'tipo' => $request->tipo
      ]);

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      $this->contenido = $imageService->processSummernoteImages(
        $request->contenido,
        "articulos",
        $this->id
      );

      $this->save();

      return $this;
    });
  }

  /**
   * Elimina el articulo y sus datos relacionados.
   *
   * @return bool|null
   */
  public function eliminar_articulo()
  {
    return DB::transaction(function () {
      //Borrar imágenes de Summernote relacionadas
      $imageService = new ImageService();
      $imageService->deleteImagesByOwner('articulos', $this->id);

      //Borrar el articulo
      return $this->delete();
    });
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
