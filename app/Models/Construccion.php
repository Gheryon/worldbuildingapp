<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\ImageService;
use App\Traits\HandlesRichTextImages;

class Construccion extends Model
{
  use HasFactory, HandlesRichTextImages;

  protected $table = 'construcciones';
  protected $primaryKey = 'id';
  public $timestamps = true;

  protected $fillable = [
    'nombre',
    'descripcion_breve',
    'aspecto',
    'historia',
    'arquitectura',
    'proposito',
    'otros',
    'estatus',
    'importancia_social',
    'tipo_construccion_id',
    'asentamiento_id',
    'fecha_construccion_id',
    'fecha_destruccion_id',
    'materiales_principales',
    'materiales_exoticos',
    'tecnica_construccion',
    'tecnologia_perdida',
    'acceso_publico',
    'rutas_acceso',
    'dificultad_acceso',
    'acceso_temporal',
    'tiene_magia_inherente',
    'propiedades_magicas',
    'fuente_poder_magico',
    'tipo_magia',
    'altitud',
    'nivel_deterioro',
    'simbolismo',
  ];

  protected $casts = [
    'tipo_construccion_id' => 'integer',
    'asentamiento_id' => 'integer',
    'fecha_construccion_id' => 'integer',
    'fecha_destruccion_id' => 'integer',
  ];

  // Mapeo: 'columna_en_db' => 'nombre_input_formulario'
  public static $richTextFields = [
    'descripcion_breve'     => 'descripcion_breve',
    'arquitectura'          => 'arquitectura',
    'materiales_principales' => 'materiales_principales',
    'tecnica_construccion'  => 'tecnica_construccion',
    'materiales_exoticos'   => 'materiales_exoticos',
    'proposito'             => 'proposito',
    'importancia_social'    => 'importancia_social',
    'historia'              => 'historia',
    'propiedades_magicas'   => 'propiedades_magicas',
    'fuente_poder_magico'   => 'fuente_poder_magico',
    'simbolismo'            => 'simbolismo',
    'rutas_acceso'          => 'rutas_acceso',
    'aspecto'               => 'aspecto',
    'otros'                 => 'otros'
  ];

   // Relaciones
  /**
   * Relación con el tipo de construcción.
   */
  public function tipo(): BelongsTo
  {
    return $this->belongsTo(TipoConstruccion::class, 'tipo_construccion_id');
  }

  public function asentamiento(): BelongsTo
  {
    return $this->belongsTo(Asentamiento::class, 'asentamiento_id');
  }

  public function fechaConstruccion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fecha_construccion_id');
  }

  public function fechaDestruccion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fecha_destruccion_id');
  }

  /**
   * Scope para filtrar y ordenar construcciones.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->with('tipo')
      ->select(
        'construcciones.id',
        'construcciones.nombre',
        'construcciones.descripcion_breve',
        'construcciones.tipo_construccion_id',
        'construcciones.tiene_magia_inherente',
        'construcciones.tecnologia_perdida',
        'construcciones.nivel_deterioro',
        'construcciones.estatus',
      )
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('construcciones.nombre', 'LIKE', "%{$search}%");
      })
      ->orderBy('construcciones.nombre', $filtros['orden'] ?? 'asc');
  }

  /**
   * Almacena una nueva construcción en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Construccion
   */
  public static function store_construccion(array $data)
  {
    return DB::transaction(function () use ($data) {
      $construccion = self::create($data);

      //Manejo de Checkboxes, en un array, si el checkbox no se marcó, la clave no existe.
      $construccion->acceso_publico        = isset($data['acceso_publico']);
      $construccion->acceso_temporal       = isset($data['acceso_temporal']);
      $construccion->tecnologia_perdida    = isset($data['tecnologia_perdida']);
      $construccion->tiene_magia_inherente = isset($data['tiene_magia_inherente']);

      // Procesado campos RichText (Summernote)
      $construccion->processRichTextImages($data, self::$richTextFields, 'construcciones');

      //Procesar Fechas. Lo importante es el año, si no hay año no se guarda fecha
      if (!empty($data['anno_construccion'])) {
        $construccion->fecha_construccion_id = Fecha::sync(null, [
          'dia'  => $data['dia_construccion'] ?? 0,
          'mes'  => $data['mes_construccion'] ?? 0,
          'anno' => $data['anno_construccion'] ?? null
        ]);
      }

      if (!empty($data['anno_destruccion'])) {
        $construccion->fecha_destruccion_id = Fecha::sync(null, [
          'dia'  => $data['dia_destruccion'] ?? 0,
          'mes'  => $data['mes_destruccion'] ?? 0,
          'anno' => $data['anno_destruccion'] ?? null,
        ]);
      }

      // Guardamos los cambios finales (rutas de imágenes y fechas)
      $construccion->save();

      return $construccion;
    });
  }

  /**
   * Actualiza una construcción existente en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Construccion
   */
  public function update_construccion(array $data)
  {
    return DB::transaction(function () use ($data) {
      //Campos básicos
      $this->fill($data);

      //Manejo de Checkboxes, en un array, si el checkbox no se marcó, la clave no existe.
      $this->acceso_publico        = isset($data['acceso_publico']);
      $this->acceso_temporal       = isset($data['acceso_temporal']);
      $this->tecnologia_perdida    = isset($data['tecnologia_perdida']);
      $this->tiene_magia_inherente = isset($data['tiene_magia_inherente']);

      // Procesado campos RichText (Summernote)
      $this->processRichTextImages($data, self::$richTextFields, 'construcciones');

      //Actualizado de fechas
      //Procesar Fechas, si existe fundacion_id o disolucion_id se actualiza, si no se crea. Si no hay año no se guarda fecha
      $this->fecha_construccion_id = Fecha::sync($this->fecha_construccion_id, [
        'dia'  => $data['dia_construccion'] ?? 0,
        'mes'  => $data['mes_construccion'] ?? 0,
        'anno' => $data['anno_construccion'] ?? null,
      ]);

      $this->fecha_destruccion_id = Fecha::sync($this->fecha_destruccion_id, [
        'dia'  => $data['dia_destruccion'] ?? 0,
        'mes'  => $data['mes_destruccion'] ?? 0,
        'anno' => $data['anno_destruccion'] ?? null,
      ]);

      return $this->save();
    });
  }

  /**
   * Elimina la construcción y sus recursos relacionados (imágenes y fechas).
   *
   * @return bool|null
   * @throws \Exception
   */
  protected static function booted()
  {
    static::deleting(function ($construccion) {
      // Llamamos al servicio para limpiar el disco y la DB
      $imageService = new \App\Services\ImageService();
      $imageService->deleteImagesByOwner('construcciones', $construccion->id);

      if ($construccion->fecha_construccion_id) {
        \App\Models\Fecha::destroy($construccion->fecha_construccion_id);
      }

      if ($construccion->fecha_destruccion_id) {
        \App\Models\Fecha::destroy($construccion->fecha_destruccion_id);
      }
    });
  }
}
