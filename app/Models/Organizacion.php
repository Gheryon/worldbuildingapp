<?php

namespace App\Models;

use App\Models\Traits\HasEscudo;
use App\Models\Traits\HasReferenceImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Organizacion extends Model
{
  use HasEscudo, HasFactory, HasReferenceImages;

  protected $table = 'organizaciones';

  protected $primaryKey = 'id';

  public $timestamps = true;

  protected $fillable = [
    'nombre',
    'gentilicio',
    'escudo',
    'estatus',
    'capital_nombre',
    'descripcion_breve',
    'lema',
    'demografia',
    'historia',
    'estructura',
    'geopolitica',
    'militar',
    'religion',
    'cultura',
    'educacion',
    'tecnologia',
    'territorio',
    'economia',
    'recursos_naturales',
    'otros',
    'lider_id',
    'asentamiento_id',
    'organizacion_padre_id',
    'tipo_organizacion_id',
    'fundacion_id',
    'disolucion_id',
  ];

  protected $casts = [
    'fundacion_id' => 'integer',
    'disolucion_id' => 'integer',
    'lider_id' => 'integer',
    'asentamiento_id' => 'integer',
    'organizacion_padre_id' => 'integer',
    'tipo_organizacion_id' => 'integer',
  ];

  // Mapeo: 'columna_en_db' => 'nombre_input_formulario'
  public static $richTextFields = [
    'demografia' => 'demografia',
    'descripcion_breve' => 'descripcion_breve',
    'estructura' => 'estructura',
    'geopolitica' => 'geopolitica',
    'militar' => 'militar',
    'cultura' => 'cultura',
    'tecnologia' => 'tecnologia',
    'educacion' => 'educacion',
    'historia' => 'historia',
    'religion' => 'religion',
    'territorio' => 'territorio',
    'economia' => 'economia',
    'recursos_naturales' => 'recursos_naturales',
    'otros' => 'otros',
  ];

  /**
   * Obtiene la información de la fecha de fundación (tabla fechas).
   */
  public function fecha_fundacion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fundacion_id'); // en laravel 10 no hace falta especificar la clave foránea si sigue la convención
  }

  /**
   * Obtiene la información de la fecha de disolución (tabla fechas).
   */
  public function fecha_disolucion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'disolucion_id');
  }

  /**
   * Obtiene las religiones asociadas a la organización (tabla pivote religion_presence).
   */
  public function religiones()
  {
    return $this->belongsToMany(Religion::class, 'religion_presence', 'organizacion', 'religion');
  }

  /**
   * Relación con el tipo de organización.
   */
  public function tipo(): BelongsTo
  {
    return $this->belongsTo(TipoOrganizacion::class, 'tipo_organizacion_id');
  }

  /**
   * Relación con el personaje que gobierna (lider).
   */
  public function lider(): BelongsTo
  {
    return $this->belongsTo(Personaje::class, 'lider_id');
  }

  /**
   * Relación con la organización de la que depende (organizacion_padre).
   */
  public function organizacion_padre(): BelongsTo
  {
    return $this->belongsTo(Organizacion::class, 'organizacion_padre_id');
  }

  /**
   * Relación con las organizaciones que dependen de esta (hijas).
   */
  public function subordinates(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany(Organizacion::class, 'organizacion_padre_id', 'id');
  }

  /**
   * Scope para filtrar y ordenar organizaciones.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->leftJoin('tipo_organizacion', 'organizaciones.tipo_organizacion_id', '=', 'tipo_organizacion.id')
      ->leftJoin('organizaciones as padres', 'organizaciones.organizacion_padre_id', '=', 'padres.id')
      ->leftJoin('personajes as lideres', 'organizaciones.lider_id', '=', 'lideres.id')
      ->select(
        'organizaciones.id',
        'organizaciones.nombre',
        'organizaciones.escudo',
        'organizaciones.estatus',
        'organizaciones.descripcion_breve',
        'organizaciones.lider_id',
        'organizaciones.organizacion_padre_id',
        'organizaciones.tipo_organizacion_id',
        DB::raw('COALESCE(tipo_organizacion.nombre, "Tipo de organización desconocido") as tipo'),
        'padres.nombre as nombre_padre',
        'lideres.nombre as nombre_lider'
      )
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('organizaciones.nombre', 'LIKE', "%{$search}%");
      })
      ->when($filtros['tipo'] ?? null, function ($q, $tipo) {
        if ($tipo > 0) {
          $q->where('organizaciones.tipo_organizacion_id', $tipo);
        }
      })
      ->orderBy('organizaciones.nombre', $filtros['orden'] ?? 'asc');
  }

  /**
   * Almacena una nueva organización en la base de datos.
   *
   * @return \App\Models\Organizacion
   */
  public static function store_organizacion(array $request)
  {
    return DB::transaction(function () use ($request) {
      // Manejo del escudo
      if (isset($request['escudo']) && $request['escudo'] instanceof \Illuminate\Http\UploadedFile) {
        $request['escudo'] = self::storeEscudoFile($request['escudo']);
      } else {
        $request['escudo'] = 'default.png';
      }

      $organizacion = self::create($request);

      // Procesado de campos de RichText (Summernote) mediante el servicio ImageService
      $imageService = app(\App\Services\ImageService::class);
      $imageService->processModelRichText($organizacion, $request, self::$richTextFields);

      // Procesar Fechas. Lo importante es el año, si no hay año no se guarda fecha
      if (! empty($request['anno_fundacion'])) {
        $organizacion->fundacion_id = Fecha::sync(null, [
          'dia' => $request['dia_fundacion'] ?? null,
          'mes' => $request['mes_fundacion'] ?? null,
          'anno' => $request['anno_fundacion'] ?? null,
        ]);
      }

      if (! empty($request['anno_disolucion'])) {
        $organizacion->disolucion_id = Fecha::sync(null, [
          'dia' => $request['dia_disolucion'] ?? null,
          'mes' => $request['mes_disolucion'] ?? null,
          'anno' => $request['anno_disolucion'] ?? null,
        ]);
      }

      // Guardado de Religiones (Tabla pivote)
      if (! empty($request['religiones']) && is_array($request['religiones'])) {
        $organizacion->religiones()->sync($request['religiones']);
      }

      // Guardamos los cambios finales (rutas de imágenes y fechas)
      $organizacion->save();

      $organizacion->subirImagenesReferencia($request['imagenes_referencia'] ?? []);

      return $organizacion;
    });
  }

  /**
   * Actualiza una organización existente en la base de datos.
   *
   * @return \App\Models\Organizacion
   */
  public function update_organizacion(array $request)
  {
    return DB::transaction(function () use ($request) {
      // Manejo del escudo (Solo si se sube uno nuevo)
      if (isset($request['escudo']) && $request['escudo'] instanceof \Illuminate\Http\UploadedFile) {
        $request['escudo'] = $this->updateEscudoFile($request['escudo']);
      }

      // Campos básicos
      $this->fill($request);

      // Procesado campos RichText (Summernote)
      $imageService = app(\App\Services\ImageService::class);
      $imageService->processModelRichText($this, $request, self::$richTextFields);

      // Actualizado de fechas
      // Procesar Fechas, si existe fundacion_id o disolucion_id se actualiza, si no se crea. Si no hay año no se guarda fecha
      if (! empty($request['anno_fundacion'])) {
        $this->fundacion_id = Fecha::sync($this->fundacion_id, [
          'dia' => $request['dia_fundacion'] ?? null,
          'mes' => $request['mes_fundacion'] ?? null,
          'anno' => $request['anno_fundacion'] ?? null,
        ]);
      }

      if (! empty($request['anno_disolucion'])) {
        $this->disolucion_id = Fecha::sync($this->disolucion_id, [
          'dia' => $request['dia_disolucion'] ?? null,
          'mes' => $request['mes_disolucion'] ?? null,
          'anno' => $request['anno_disolucion'] ?? null,
        ]);
      }

      // Sincronizar religiones (tabla pivote)
      if (isset($request['religiones']) && is_array($request['religiones'])) {
        // Sincroniza los IDs del array, eliminando los que ya no estén
        $this->religiones()->sync($request['religiones']);
      } else {
        // Si el campo viene vacío o no viene (y quieres desvincular todas)
        $this->religiones()->detach();
      }

      $this->subirImagenesReferencia($request['imagenes_referencia'] ?? []);
      
      return $this->save();
    });
  }

  protected static function booted()
  {
    static::deleting(function ($organizacion) {
      // Borrado del escudo físico (si no es el default)
      $organizacion->deleteEscudoFile();

      // Borrado de imágenes de Summernote
      app(\App\Services\ImageService::class)->deleteImagesByOwner('organizaciones', $organizacion->id);

      // Borrado de relaciones con religiones (tabla pivote)
      $organizacion->religiones()->detach();

      // Desvincular asentamientos controlados
      \App\Models\Asentamiento::where('organizacion_id', $organizacion->id)
        ->update(['organizacion_id' => null]);

      // Desvincular organizaciones subordinadas
      $organizacion->subordinates()->update(['organizacion_padre_id' => null]);

      // Borrado de fechas
      if ($organizacion->fundacion_id) {
        \App\Models\Fecha::destroy($organizacion->fundacion_id);
      }

      if ($organizacion->disolucion_id) {
        \App\Models\Fecha::destroy($organizacion->disolucion_id);
      }
    });
  }
}
