<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\ImageService;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Lugar extends Model
{
  use HasFactory;

  protected $table = 'lugares';
  protected $primaryKey = 'id';
  public $timestamps = true;

  protected $fillable = [
    'nombre',
    'es_secreto',
    'descripcion_breve',
    'tipo_lugar_id',
    'geografia',
    'ecosistema',
    'clima',
    'fenomeno_unico',
    'estacionalidad',
    'nivel_peligro',
    'tipo_peligro',
    'dificultad_acceso',
    'flora_fauna',
    'recursos',
    'recursos_naturales',
    'otros_nombres',
    'historia',
    'rumores',
    'otros',
  ];

  protected $casts = [
    'tipo_lugar_id' => 'integer',
  ];

  // Mapeo: 'columna_en_db' => 'nombre_input_formulario'
  public static $richTextFields = [
    'descripcion_breve' => 'descripcion_breve',
    'geografia'         => 'geografia',
    'ecosistema'        => 'ecosistema',
    'clima'             => 'clima',
    'fenomeno_unico'    => 'fenomeno_unico',
    'flora_fauna'       => 'flora_fauna',
    'recursos'          => 'recursos',
    'historia'          => 'historia',
    'rumores'           => 'rumores',
    'otros'             => 'otros'
  ];

  /**
   * Relación con el tipo de lugar.
   */
  public function tipo(): BelongsTo
  {
    return $this->belongsTo(TipoLugar::class, 'tipo_lugar_id');
  }

  /** Relación con los conflictos que han ocurrido en este lugar.
   * Se usa morphMany porque un conflicto puede ocurrir en un lugar natural o en un asentamiento.
   */
  public function conflictos(): \Illuminate\Database\Eloquent\Relations\MorphMany
  {
    return $this->morphMany(Conflicto::class, 'ubicacion_principal');
  }

  /**
   * Definición del Atributo PeligroConfig
   * Esto crea una propiedad virtual llamada 'peligro_config'
   * Acceso en la vista como: $lugar->peligro_config
   */
  protected function peligroConfig(): Attribute
  {
    return Attribute::make(
      get: function () {
        $nivel = $this->nivel_peligro ?? 'Desconocido';

        $config = [
          'Ninguno'     => ['class' => 'badge-success',           'icons' => 0],
          'Bajo'        => ['class' => 'badge-info',              'icons' => 0],
          'Moderado'    => ['class' => 'badge-warning text-dark', 'icons' => 0],
          'Alto'        => ['class' => 'badge-danger',            'icons' => 1],
          'Mortal'      => ['class' => 'badge-danger',            'icons' => 2],
          'Desconocido' => ['class' => 'badge-secondary',          'icons' => 1]
        ];

        // Devolvemos como objeto para acceder con -> en la vista
        return (object) ($config[$nivel] ?? $config['Desconocido']);
      },
    );
  }

  /**
   * Scope para filtrar y ordenar lugares.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->leftJoin('tipo_lugar', 'lugares.tipo_lugar_id', '=', 'tipo_lugar.id')
      ->select(
        'lugares.id',
        'lugares.nombre',
        'lugares.tipo_lugar_id',
        'lugares.descripcion_breve',
        'lugares.nivel_peligro',
        'lugares.tipo_peligro',
        'lugares.dificultad_acceso',
        DB::raw('COALESCE(tipo_lugar.nombre, "Tipo de lugar desconocido") as tipo_lugar')
      )
      ->where('lugares.id', '!=', 0)
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('lugares.nombre', 'LIKE', "%{$search}%");
      })
      ->orderBy('lugares.nombre', $filtros['orden'] ?? 'asc');
  }

  /**
   * Almacena un nuevo lugar en la base de datos.
   *
   * @param array $request
   * @return \App\Models\Lugar
   */
  public static function store_lugar(array $request)
  {
    return DB::transaction(function () use ($request) {
      $lugar = self::create($request);

      // Procesado de campos de Summernote
      $lugar->processRichTextImages($request, self::$richTextFields, 'lugares');

      // Guardamos los cambios finales (rutas de imágenes y fechas)
      $lugar->save();

      return $lugar;
    });
  }

  /**
   * Actualiza un lugar existente en la base de datos.
   *
   * @param array $request
   * @return \App\Models\Lugar
   */
  public function update_lugar(array $request)
  {
    return DB::transaction(function () use ($request) {
      //Campos básicos
      $this->fill($request);

      // Procesado campos RichText (Summernote)
      $this->processRichTextImages($request, self::$richTextFields, 'lugares');

      return $this->save();
    });
  }

  /**
   * Elimina el lugar y todos sus recursos asociados (archivos y registros).
   *
   * @return bool|null
   * @throws \Exception
   */
  protected static function booted()
  {
    static::deleting(function ($lugar) {
      // Desvincular lugares
      // Usamos el Query Builder para un update masivo rápido sin disparar eventos de Asentamiento
      \App\Models\Conflicto::where('ubicacion_principal', $lugar->id)
        ->update(['ubicacion_principal' => null]);

      // Llamamos al servicio para limpiar el disco y la DB
      app(\App\Services\ImageService::class)->deleteImagesByOwner('lugares', $lugar->id);

    });
  }
}
