<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Services\ImageService;

class Asentamiento extends Model
{
  use HasFactory;

  protected $table = 'asentamientos';
  protected $primaryKey = 'id';
  public $timestamps=true;

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
  public static function store_asentamiento($request)
  {
    return DB::transaction(function () use ($request) {
      $asentamiento = self::create([
        'nombre' => $request->nombre,
        'gentilicio' => $request->gentilicio,
        'poblacion' => $request->poblacion,
        'estatus' => $request->estatus,
        'recurso_principal' => $request->recurso_principal,
        'nivel_riqueza' => $request->nivel_riqueza,
        'organizacion_id' => $request->select_owner,
        'gobernante_id' => $request->select_gobernante,
        'tipo_asentamiento_id' => $request->select_tipo,
      ]);

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      $camposRichText = [
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

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          $asentamiento->$columna = $imageService->processSummernoteImages(
            $request->$input,
            "asentamientos",
            $asentamiento->id
          );
        }
      }

      //Procesar Fechas. Lo importante es el año, si no hay año no se guarda fecha
      if ($request->filled('anno_fundacion')) {
        $asentamiento->fundacion_id = Fecha::store_fecha(
          $request->dia_fundacion,
          $request->mes_fundacion,
          $request->anno_fundacion
        );
      }

      if ($request->filled('anno_disolucion')) {
        $asentamiento->disolucion_id = Fecha::store_fecha(
          $request->dia_disolucion,
          $request->mes_disolucion,
          $request->anno_disolucion
        );
      }

      // Guardamos los cambios finales (rutas de imágenes y fechas)
      $asentamiento->save();

      return $asentamiento;
    });
  }

  /**
   * Actualiza un asentamiento existente en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Asentamiento
   */
  public function update_asentamiento($request)
  {
    return DB::transaction(function () use ($request) {
      //Campos básicos
      $this->fill([
        'nombre' => $request->nombre,
        'gentilicio' => $request->gentilicio,
        'poblacion' => $request->poblacion,
        'estatus' => $request->estatus,
        'recurso_principal' => $request->recurso_principal,
        'nivel_riqueza' => $request->nivel_riqueza,
        'organizacion_id' => $request->select_owner,
        'gobernante_id' => $request->select_gobernante,
        'tipo_asentamiento_id' => $request->select_tipo,
      ]);

      // Procesado campos RichText (Summernote)
      $imageService = new ImageService();
      $campos = [
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

      foreach ($campos as $campo) {
        if ($request->filled($campo)) {
          $this->$campo = $imageService->processSummernoteImages(
            $request->$campo,
            "organizaciones",
            $this->id
          );
        }
      }

      //Actualizado de fechas
      //Procesar Fechas, si existe fundacion_id o disolucion_id se actualiza, si no se crea. Si no hay año no se guarda fecha
      if ($this->fundacion_id) {
        Fecha::update_fecha($request->dia_fundacion, $request->mes_fundacion, $request->anno_fundacion, $this->fundacion_id);
      } else {
        if ($request->filled('anno_fundacion')) {
          $this->fundacion_id = Fecha::store_fecha($request->dia_fundacion, $request->mes_fundacion, $request->anno_fundacion);
        }
      }

      if ($this->disolucion_id) {
        Fecha::update_fecha($request->dia_disolucion, $request->mes_disolucion, $request->anno_disolucion, $this->disolucion_id);
      } else {
        if ($request->filled('anno_disolucion')) {
          $this->disolucion_id = Fecha::store_fecha($request->dia_disolucion, $request->mes_disolucion, $request->anno_disolucion);
        }
      }

      return $this->save();
    });
  }

  /**
   * Elimina el asentamiento y todos sus recursos asociados (archivos y registros).
   *
   */
  public function delete_asentamiento()
  {
    return DB::transaction(function () {
      //Borrar fechas asociadas
      if ($this->fundacion_id) {
        Fecha::destroy($this->fundacion_id);
      }
      if ($this->disolucion_id) {
        Fecha::destroy($this->disolucion_id);
      }

      //Borrar imágenes de Summernote usando el servicio
      $imageService = new ImageService();
      $imageService->deleteImagesByOwner('asentamientos', $this->id);

      //Eliminar el asentamiento
      return $this->delete();
    });
  }

}
