<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\ImageService;

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
   * Relación con los personajes relevantes.
   */
  public function personajes_relevantes()
  {
    return $this->belongsToMany(Personaje::class, 'personajes_relevantes', 'relato_id', 'personaje_id')
      ->withTimestamps();
  }

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
      ->when(isset($filtros['tipo']), function ($q) use ($filtros) {
        if ($filtros['tipo'] === 'Relato') {
            // Si se pide explícitamente Relato
            $q->where('articulos_genericos.tipo', 'Relato');
        } elseif ($filtros['tipo'] !== 'all') {
            // Si se pide otro tipo específico (ej: 'Noticia')
            $q->where('articulos_genericos.tipo', $filtros['tipo']);
        } else {
            // Si el tipo es 'all', mostramos todo MENOS relatos
            $q->where('articulos_genericos.tipo', '!=', 'Relato');
        }
    }, function ($q) {
        // Si no se pasa el parámetro 'tipo' en absoluto, protegemos el listado
        $q->where('articulos_genericos.tipo', '!=', 'Relato');
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

      // Sincronización de personajes (solo si vienen en el request)
      // sync() elimina los que no estén en el array y añade los nuevos.
      if ($request->has('personajes')) {
        $this->personajes_relevantes()->sync($request->input('personajes'));
      } else {
        // Si se desmarcan todos, se limpia la tabla pivot
        $this->personajes_relevantes()->detach();
      }

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
      //Limpiar relación con personajes (tabla pivot) sólo si es un relato, los otros casos no tienen personajes relevantes
      if ($this->tipo === 'Relato') {
        $this->personajes_relevantes()->detach();
      }

      //Borrar imágenes de Summernote relacionadas
      $imageService = new ImageService();
      $imageService->deleteImagesByOwner('articulos', $this->id);

      //Borrar el articulo
      return $this->delete();
    });
  }
}
