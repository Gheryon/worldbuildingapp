<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\HandlesRichTextImages;

class Personaje extends Model
{
  use HasFactory, HandlesRichTextImages;

  protected $table = 'personajes';
  protected $primaryKey = 'id';
  public $timestamps = true;

  protected $fillable = [
    'nombre',
    'nombre_familia',
    'apellidos',
    'apodo',
    'profesion',
    'lugar_nacimiento',
    'causa_fallecimiento',
    'descripcion_fisica',
    'descripcion_corta',
    'personalidad',
    'salud',
    'personalidad',
    'deseos',
    'miedos',
    'magia',
    'educacion',
    'biografia',
    'religion',
    'familia',
    'politica',
    'retrato',
    'especie_id',
    'nacimiento_id',
    'fallecimiento_id',
    'sexo',
    'otros'
  ];

  protected $casts = [
    'nacimiento_id' => 'integer',
    'fallecimiento_id' => 'integer',
    'lugar_nacimiento' => 'integer',
    'especie_id' => 'integer',
  ];

  // Mapeo: 'columna_en_db' => 'nombre_input_formulario'
  public static $richTextFields = [
    'descripcion_fisica' => 'descripcion_fisica',
    'descripcion_corta' => 'descripcion_corta',
    'salud' => 'salud',
    'personalidad' => 'personalidad',
    'deseos' => 'deseos',
    'miedos' => 'miedos',
    'magia' => 'magia',
    'educacion' => 'educacion',
    'biografia' => 'biografia',
    'religion' => 'religion',
    'familia' => 'familia',
    'politica' => 'politica',
    'otros' => 'otros'
  ];

  /**
   * Obtiene la especie a la que pertenece el personaje.
   */
  public function especie(): BelongsTo
  {
    return $this->belongsTo(Especie::class, 'especie_id');
  }

  /**
   * Obtiene la información de la fecha de nacimiento (tabla fechas).
   */
  public function fecha_nacimiento(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'nacimiento_id');
  }

  /**
   * Obtiene la información de la fecha de fallecimiento (tabla fechas).
   */
  public function fecha_fallecimiento(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fallecimiento_id');
  }

  /**
   * Obtiene las organizaciones en las que este personaje es el gobernante (ruler).
   */
  public function organizacionesGobernadas(): HasMany
  {
    return $this->hasMany(Organizacion::class, 'lider_id', 'id');
  }

  public function lugar_nacimiento()
  {
    return $this->morphTo();
  }

  /**
   * Scope para filtrar y ordenar personajes.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->with('especie:id,nombre')
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('personajes.nombre', 'LIKE', "%{$search}%");
      })
      ->when(!empty($filtros['especie']), function ($q) use ($filtros) {
        $q->where('personajes.especie_id', $filtros['especie']);
      })
      ->orderBy('personajes.nombre', $filtros['orden'] ?? 'asc');
  }

  /**
   * Obtiene la edad del personaje si está vivo o murió
   * 
   * @param Fecha $nacimiento
   * @param Fecha $fin
   * @return string
   */
  public function getEdadAttribute(Fecha $nacimiento, Fecha $fin): String
  {
    //el mundo no se rige por nuestro calendario, así que se convierte todo a días para hacer la resta
    $dias_nacimiento = $nacimiento->anno * 365 + $nacimiento->mes * 30 + $nacimiento->dia;
    $dias_fallecimiento = $fin->anno * 365 + $fin->mes * 30 + $fin->dia;

    $edad = ($dias_fallecimiento - $dias_nacimiento) / 365;
    $edad = (int)$edad . " años.";

    return $edad;
  }

  /**
   * Almacena un nuevo personaje en la base de datos.
   *
   * @param array $request
   * @return \App\Models\personaje
   */
  public static function store_personaje(array $request)
  {
    return DB::transaction(function () use ($request) {
      // Manejo del retrato
      if (isset($request['retrato']) && $request['retrato'] instanceof \Illuminate\Http\UploadedFile) {
        $path = $request['retrato']->store('retratos', 'public');
        $request['retrato'] = basename($path);
      } else {
        $request['retrato'] = "default.png";
      }

      if (isset($request['select_especie'])) {
        $request['especie_id'] = $request['select_especie'];
      }

      $personaje = self::create($request);

      // Procesado de campos de Summernote
      $personaje->processRichTextImages($request, self::$richTextFields, 'personajes');

      //Procesar Fechas. Lo importante es el año, si no hay año no se guarda fecha
      if (!empty($request['anno_nacimiento'])) {
        $personaje->nacimiento_id = Fecha::sync(null, [
          'dia'  => $request['dia_nacimiento'] ?? null,
          'mes'  => $request['mes_nacimiento'] ?? null,
          'anno' => $request['anno_nacimiento'] ?? null
        ]);
      }

      if (!empty($request['anno_fallecimiento'])) {
        $personaje->fallecimiento_id = Fecha::sync(null, [
          'dia'  => $request['dia_fallecimiento'] ?? null,
          'mes'  => $request['mes_fallecimiento'] ?? null,
          'anno' => $request['anno_fallecimiento'] ?? null
        ]);
      }

      $personaje->save();

      return $personaje;
    });
  }

  /**
   * Actualiza un personaje existente en la base de datos.
   *
   * @param array $request
   * @return \App\Models\personaje
   */
  public function update_personaje(array $request)
  {
    return DB::transaction(function () use ($request) {
      // Manejo del retrato
      if (isset($request['retrato']) && $request['retrato'] instanceof \Illuminate\Http\UploadedFile) {
        $path = $request['retrato']->store('retratos', 'public');
        $request['retrato'] = basename($path);
      }

      // Asignación de campos básicos
      $this->fill($request);

      // Procesado de campos de Summernote
      $this->processRichTextImages($request, self::$richTextFields, 'personajes');

      //Actualizado de fechas
      //Procesar Fechas, si existe nacimiento_id o fallecimiento_id se actualiza, si no se crea. Si no hay año no se guarda fecha
      if (!empty($request['anno_nacimiento'])) {
        $this->nacimiento_id = Fecha::sync($this->nacimiento_id, [
          'dia'  => $request['dia_nacimiento'] ?? null,
          'mes'  => $request['mes_nacimiento'] ?? null,
          'anno' => $request['anno_nacimiento'] ?? null
        ]);
      }

      if (!empty($request['anno_fallecimiento'])) {
        $this->fallecimiento_id = Fecha::sync($this->fallecimiento_id, [
          'dia'  => $request['dia_fallecimiento'] ?? null,
          'mes'  => $request['mes_fallecimiento'] ?? null,
          'anno' => $request['anno_fallecimiento'] ?? null
        ]);
      }

      return $this->save();
    });
  }

  /**
   * Elimina el personaje y sus recursos relacionados (imágenes y fechas).
   *
   * @return bool|null
   * @throws \Exception
   */
  protected static function booted()
  {
    static::deleting(function ($personaje) {
      // Llamamos al servicio para limpiar el disco y la DB
      //$imageService = new \App\Services\ImageService();
      //$imageService->deleteImagesByOwner('personajes', $personaje->id);
      //Versión alternativa con service container, para evitar inyección directa y facilitar testing/mocking
      app(\App\Services\ImageService::class)->deleteImagesByOwner('personajes', $personaje->id);

      //Borrado de fechas
      if ($personaje->nacimiento_id) {
        \App\Models\Fecha::destroy($personaje->nacimiento_id);
      }

      if ($personaje->fallecimiento_id) {
        \App\Models\Fecha::destroy($personaje->fallecimiento_id);
      }
    });
  }
}
