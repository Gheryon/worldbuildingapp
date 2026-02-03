<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Services\ImageService;
use Exception;

class Especie extends Model
{
  use HasFactory;

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
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Especie
   */
  public static function store_especie($request)
  {
    return DB::transaction(function () use ($request) {
      // Crear registro
      $especie = self::create([
        'nombre' => $request->nombre,
        'reino' => $request->reino,
        'clase_taxonomica' => $request->clase_taxonomica,
        'locomocion' => $request->locomocion,
        'organizacion_social' => $request->organizacion_social,
        'edad' => $request->edad,
        'mortalidad' => $request->mortalidad,
        'peso' => $request->peso,
        'altura' => $request->altura,
        'longitud' => $request->longitud,
        'estatus' => $request->estatus,
        'dieta' => $request->dieta,
        'rareza' => $request->rareza,
      ]);

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      $camposRichText = [
        'anatomia' => 'anatomia',
        'alimentacion' => 'alimentacion',
        'reproduccion' => 'reproduccion',
        'dimorfismo_sexual' => 'dimorfismo_sexual',
        'distribucion' => 'distribucion',
        'habilidades' => 'habilidades',
        'domesticacion' => 'domesticacion',
        'explotacion' => 'explotacion',
        'otros' => 'otros'
      ];

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          $especie->$columna = $imageService->processSummernoteImages(
            $request->$input,
            "especies",
            $especie->id
          );
        }
      }

      $especie->save();

      return $especie;
    });
  }

  /**
   * Actualiza una especie existente en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Especie
   */
  public function update_especie($request)
  {
    return DB::transaction(function () use ($request) {
      $this->fill([
        'nombre' => $request->nombre,
        'reino' => $request->reino,
        'clase_taxonomica' => $request->clase_taxonomica,
        'locomocion' => $request->locomocion,
        'organizacion_social' => $request->organizacion_social,
        'edad' => $request->edad,
        'mortalidad' => $request->mortalidad,
        'peso' => $request->peso,
        'altura' => $request->altura,
        'longitud' => $request->longitud,
        'estatus' => $request->estatus,
        'dieta' => $request->dieta,
        'rareza' => $request->rareza,
      ]);

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      $camposRichText = [
        'anatomia' => 'anatomia',
        'alimentacion' => 'alimentacion',
        'reproduccion' => 'reproduccion',
        'dimorfismo_sexual' => 'dimorfismo_sexual',
        'distribucion' => 'distribucion',
        'habilidades' => 'habilidades',
        'domesticacion' => 'domesticacion',
        'explotacion' => 'explotacion',
        'otros' => 'otros'
      ];

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          $this->$columna = $imageService->processSummernoteImages(
            $request->$input,
            "especies",
            $this->id
          );
        }
      }

      $this->save();

      return $this;
    });
  }

  /**
   * Elimina la especie y sus recursos asociados (imágenes).
   * * @return void
   */
  public function delete_especie()
  {
    return DB::transaction(function () {
      // Obtener y eliminar imágenes físicas del disco
      $imagenes = DB::table('imagenes')
        ->where('table_owner', 'especies')
        ->where('owner', $this->id)
        ->get();

      foreach ($imagenes as $imagen) {
        $path = public_path("/storage/imagenes/{$imagen->nombre}");
        if (file_exists($path)) {
          unlink($path);
        }
      }

      //Limpiar registros de la tabla imagenes
      DB::table('imagenes')
        ->where('table_owner', 'especies')
        ->where('owner', $this->id)
        ->delete();

      // Eliminar la especie
      return $this->delete();
    });
  }
}
