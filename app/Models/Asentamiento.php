<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\HandlesRichTextImages;

class Asentamiento extends Model
{
  use HasFactory, HandlesRichTextImages;

  protected $table = 'asentamientos';
  protected $primaryKey = 'id';
  public $timestamps = true;

  protected $fillable = [
    'nombre',
    'gentilicio',
    'estatus',
    'recurso_principal',
    'nivel_riqueza',
    'poblacion',
    'demografia',
    'gobierno',
    'defensas',
    'ejercito',
    'ubicacion_detalles',
    'descripcion',
    'infraestructura',
    'historia',
    'economia',
    'arquitectura',
    'cultura',
    'geografia',
    'clima',
    'recursos',
    'otros',
    'lugar_id',
    'tipo_asentamiento_id',
    'fundacion_id',
    'disolucion_id',
    'organizacion_id',
    'gobernante_id'
  ];

  protected $casts = [
    'fundacion_id' => 'integer',
    'disolucion_id' => 'integer',
    'lugar_id' => 'integer',
    'organizacion_id' => 'integer',
    'gobernante_id' => 'integer',
    'tipo_asentamiento_id' => 'integer',
  ];

  // Mapeo: 'columna_en_db' => 'nombre_input_formulario'
  public static $richTextFields = [
    'descripcion' => 'descripcion',
    'geografia' => 'geografia',
    'ubicacion_detalles' => 'ubicacion_detalles',
    'clima' => 'clima',
    'demografia' => 'demografia',
    'cultura' => 'cultura',
    'arquitectura' => 'arquitectura',
    'infraestructura' => 'infraestructura',
    'gobierno' => 'gobierno',
    'defensas' => 'defensas',
    'ejercito' => 'ejercito',
    'economia' => 'economia',
    'recursos' => 'recursos',
    'historia' => 'historia',
    'otros' => 'otros'
  ];

  /**
   * Relación con el tipo de asentamiento.
   */
  public function tipo(): BelongsTo
  {
    return $this->belongsTo(TipoAsentamiento::class, 'tipo_asentamiento_id');
  }

  /**
   * Obtiene la información de la fecha de fundación (tabla fechas).
   */
  public function fecha_fundacion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fundacion_id'); //en laravel 10 no hace falta especificar la clave foránea si sigue la convención
  }

  /**
   * Obtiene la información de la fecha de disolución (tabla fechas).
   */
  public function fecha_disolucion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'disolucion_id');
  }

  /**
   * Relación con organización dueña.
   */
  public function controlado_por(): BelongsTo
  {
    return $this->belongsTo(Organizacion::class, 'organizacion_id');
  }

  /**
   * Relación con personaje gobernante.
   */
  public function gobernante(): BelongsTo
  {
    return $this->belongsTo(Personaje::class, 'gobernante_id');
  }

  /**
   * Relación con los conflictos que han ocurrido en este asentamiento.
   * Se usa morphMany porque un conflicto puede ocurrir en un lugar natural o en un asentamiento.
   */
  public function conflictos(): \Illuminate\Database\Eloquent\Relations\MorphMany
  {
    return $this->morphMany(Conflicto::class, 'ubicacion_principal');
  }

  /**
   * Scope para filtrar y ordenar asentamientos.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->leftJoin('tipo_asentamiento', 'asentamientos.tipo_asentamiento_id', '=', 'tipo_asentamiento.id')
      ->select(
        'asentamientos.id',
        'asentamientos.nombre',
        'asentamientos.tipo_asentamiento_id',
        DB::raw('COALESCE(tipo_asentamiento.nombre, "Tipo de asentamiento desconocido") as tipo')
      )
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('asentamientos.nombre', 'LIKE', "%{$search}%");
      })
      ->when($filtros['tipo'] ?? null, function ($q, $tipo) {
        if ($tipo > 0) $q->where('asentamientos.tipo_asentamiento_id', $tipo);
      })
      ->orderBy('asentamientos.nombre', $filtros['orden'] ?? 'asc');
  }

  /**
   * Almacena un nuevo asentamiento en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Asentamiento
   */
  public static function store_asentamiento(array $request)
  {
    return DB::transaction(function () use ($request) {
      $asentamiento = self::create($request);

      // Procesado de campos de RichText (Summernote)
      $asentamiento->processRichTextImages($request, self::$richTextFields, 'asentamientos');

      //Procesar Fechas. Lo importante es el año, si no hay año no se guarda fecha
      if (!empty($request['anno_fundacion'])) {
        $asentamiento->fecha_inicio_id = Fecha::sync(null, [
          'dia'  => $request['dia_fundacion'] ?? 0,
          'mes'  => $request['mes_fundacion'] ?? 0,
          'anno' => $request['anno_fundacion'] ?? null
        ]);
      }

      if (!empty($request['anno_disolucion'])) {
        $asentamiento->fecha_fin_id = Fecha::sync(null, [
          'dia'  => $request['dia_disolucion'] ?? 0,
          'mes'  => $request['mes_disolucion'] ?? 0,
          'anno' => $request['anno_disolucion'] ?? null
        ]);
      }

      // Guardamos los cambios finales (rutas de imágenes y fechas)
      $asentamiento->save();

      return $asentamiento;
    });
  }

  /**
   * Actualiza un asentamiento existente en la base de datos.
   *
   * @param array $request
   * @return \App\Models\Asentamiento
   */
  public function update_asentamiento(array $request)
  {
    return DB::transaction(function () use ($request) {
      //Campos básicos
      $this->fill($request);

      // Procesado de campos de RichText (Summernote)
      $this->processRichTextImages($request, self::$richTextFields, 'asentamientos');

      //Actualizado de fechas, si existe *_id se actualiza, si no se crea. Si no hay año no se guarda fecha
      if (!empty($request['anno_fundacion'])) {
        $this->fecha_inicio_id = Fecha::sync($this->fundacion_id, [
          'dia'  => $request['dia_fundacion'] ?? 0,
          'mes'  => $request['mes_fundacion'] ?? 0,
          'anno' => $request['anno_fundacion'] ?? null
        ]);
      }

      if (!empty($request['anno_disolucion'])) {
        $this->fecha_fin_id = Fecha::sync($this->disolucion_id, [
          'dia'  => $request['dia_disolucion'] ?? 0,
          'mes'  => $request['mes_disolucion'] ?? 0,
          'anno' => $request['anno_disolucion'] ?? null
        ]);
      }
      return $this->save();
    });
  }

  /**
   * Elimina el asentamiento y sus recursos relacionados (imágenes y fechas).
   *
   * @return bool|null
   * @throws \Exception
   */
  protected static function booted()
  {
    static::deleting(function ($asentamiento) {
      // Llamamos al servicio para limpiar el disco y la DB
      //$imageService = new \App\Services\ImageService();
      //$imageService->deleteImagesByOwner('asentamientos', $asentamiento->id);
      //Versión alternativa con service container, para evitar inyección directa y facilitar testing/mocking
      app(\App\Services\ImageService::class)->deleteImagesByOwner('asentamientos', $asentamiento->id);

      //Borrado de fechas
      if ($asentamiento->fecha_inicio_id) {
        \App\Models\Fecha::destroy($asentamiento->fecha_inicio_id);
      }

      if ($asentamiento->fecha_fin_id) {
        \App\Models\Fecha::destroy($asentamiento->fecha_fin_id);
      }
    });
  }
}
