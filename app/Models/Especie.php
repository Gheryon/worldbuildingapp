<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\HandlesRichTextImages;
use Exception;

class Especie extends Model
{
  use HasFactory, HandlesRichTextImages;

  protected $table = 'especies';
  protected $primaryKey = 'id';
  public $timestamps = true;

  protected $fillable = [
    'nombre',
    'reino',
    'clase_taxonomica',
    'locomocion',
    'organizacion_social',
    'edad',
    'mortalidad',
    'peso',
    'altura',
    'longitud',
    'estatus',
    'dieta',
    'rareza',
    'anatomia',
    'alimentacion',
    'reproduccion',
    'dimorfismo_sexual',
    'distribucion',
    'habilidades',
    'domesticacion',
    'explotacion',
    'otros',
  ];

  // Mapeo: 'columna_en_db' => 'nombre_input_formulario'
  public static $richTextFields = [
    'anatomia'          => 'anatomia',
    'alimentacion'      => 'alimentacion',
    'reproduccion'      => 'reproduccion',
    'dimorfismo_sexual' => 'dimorfismo_sexual',
    'distribucion'      => 'distribucion',
    'habilidades'       => 'habilidades',
    'domesticacion'     => 'domesticacion',
    'explotacion'       => 'explotacion',
    'otros'             => 'otros'
  ];

  /**
   * Scope para filtrar y ordenar organizaciones.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->select(
      'id',
      'nombre',
      'reino',
      'peso',
      'altura',
      'longitud',
      'edad',
      'rareza',
      'estatus',
      'clase_taxonomica',
      'organizacion_social',
      'dieta',
      'mortalidad',
    )
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('especies.nombre', 'LIKE', "%{$search}%");
      })
      ->orderBy('especies.nombre', $filtros['orden'] ?? 'asc');
  }

  /**
   * Almacena una nueva especie en la base de datos.
   *
   * @param array $request
   * @return \App\Models\Especie
   */
  public static function store_especie(array $request)
  {
    return DB::transaction(function () use ($request) {
      // Crear registro
      $especie = self::create($request);

      // Procesado de campos de RichText (Summernote)
      $especie->processRichTextImages($request, self::$richTextFields, 'especies');

      $especie->save();

      return $especie;
    });
  }

  /**
   * Actualiza una especie existente en la base de datos.
   *
   * @param array $request
   * @return \App\Models\Especie
   */
  public function update_especie(array $request)
  {
    return DB::transaction(function () use ($request) {
      $this->fill($request);

      // Procesado de campos de RichText (Summernote)
      $this->processRichTextImages($request, self::$richTextFields, 'especies');

      $this->save();

      return $this;
    });
  }

  /**
   * Elimina la especie y sus recursos asociados (imágenes).
   * @return bool|null
   * @throws \Exception
   */
  protected static function booted()
  {
    static::deleting(function ($especie) {
      // Llamamos al servicio para limpiar el disco y la DB
      //$imageService = new \App\Services\ImageService();
      //$imageService->deleteImagesByOwner('especies', $especie->id);
      //Versión alternativa con service container, para evitar inyección directa y facilitar testing/mocking
      app(\App\Services\ImageService::class)->deleteImagesByOwner('especies', $especie->id);
    });
  }
}
