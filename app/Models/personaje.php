<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

class personaje extends Model
{
  use HasFactory;

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
    return $query->leftJoin('especies', 'personajes.especie_id', '=', 'especies.id')
      ->select(
        'personajes.id',
        'personajes.nombre',
        'personajes.retrato',
        'personajes.sexo',
        'personajes.especie_id',
        DB::raw('COALESCE(especies.nombre, "Especie desconocida") as especie')
      )
      ->where('personajes.id', '!=', 0)
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('personajes.nombre', 'LIKE', "%{$search}%");
      })
      ->when($filtros['especie'] ?? null, function ($q, $especie) {
        if ($especie > 0) $q->where('personajes.especie_id', $especie);
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
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\personaje
   */
  public static function store_personaje($request)
  {
    return DB::transaction(function () use ($request) {
      $personaje =self::create([
        'nombre' => $request->nombre,
        'apellidos' => $request->apellidos,
        'nombre_familia' => $request->nombre_familia,
        'apodo' => $request->apodo,
        'profesion' => $request->profesion,
        //'lugar_nacimiento' => $request->lugar_nacimiento,//aún sin implementar
        'causa_fallecimiento' => $request->causa_fallecimiento,
        'especie_id' => $request->select_especie,
        'sexo' => $request->sexo
      ]);

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      $camposRichText = [
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

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          $personaje->$columna = $imageService->processSummernoteImages(
            $request->$input,
            "personajes",
            $personaje->id
          );
        }
      }

      // Manejo del Retrato
      if ($request->hasFile('retrato')) {
        $path = $request->file('retrato')->store('retratos', 'public');
        $personaje->retrato = basename($path);
      } else {
        $personaje->retrato = "default.png";
      }
      
      //Procesar Fechas. Lo importante es el año, si no hay año no se guarda fecha
      if ($request->filled('anno_nacimiento')) {
        $personaje->nacimiento_id = Fecha::store_fecha(
          $request->dia_nacimiento,
          $request->mes_nacimiento,
          $request->anno_nacimiento
        );
      }

      if ($request->filled('anno_fallecimiento')) {
        $personaje->fallecimiento_id = Fecha::store_fecha(
          $request->dia_fallecimiento,
          $request->mes_fallecimiento,
          $request->anno_fallecimiento
        );
      }

      $personaje->save();

      return $personaje;
    });
  }

  /**
   * Actualiza un personaje existente en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\personaje
   */
  public function update_personaje($request)
  {
    return DB::transaction(function () use ($request) {
      // Asignación de campos básicos
    $this->fill([
      'nombre' => $request->nombre,
      'apellidos' => $request->apellidos,
      'nombre_familia' => $request->nombre_familia,
      'apodo' => $request->apodo,
      'profesion' => $request->profesion,
      //'lugar_nacimiento' => $request->lugar_nacimiento,//aún sin implementar
      'causa_fallecimiento' => $request->causa_fallecimiento,
      'especie_id' => $request->select_especie,
      'sexo' => $request->sexo
    ]);

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      $camposRichText = [
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

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          $this->$columna = $imageService->processSummernoteImages(
            $request->$input,
            "personajes",
            $this->id
          );
        }
      }

      // Manejo de retrato con Storage
      if ($request->hasFile('retrato')) {
        if ($this->retrato && $this->retrato !== 'default.png') {
          Storage::disk('public')->delete('retratos/' . $this->retrato);
        }
        $path = $request->file('retrato')->store('retratos', 'public');
        $this->retrato = basename($path);
      }

      //Actualizado de fechas
      //Procesar Fechas, si existe nacimiento_id o fallecimiento_id se actualiza, si no se crea. Si no hay año no se guarda fecha
      if ($this->nacimiento_id) {
        Fecha::update_fecha($request->dia_nacimiento, $request->mes_nacimiento, $request->anno_nacimiento, $this->nacimiento_id);
      } else {
        if ($request->filled('anno_nacimiento')) {
          $this->nacimiento_id = Fecha::store_fecha($request->dia_nacimiento, $request->mes_nacimiento, $request->anno_nacimiento);
        }
      }

      if ($this->fallecimiento_id) {
        Fecha::update_fecha($request->dia_fallecimiento, $request->mes_fallecimiento, $request->anno_fallecimiento, $this->fallecimiento_id);
      } else {
        if ($request->filled('anno_fallecimiento')) {
          $this->fallecimiento_id = Fecha::store_fecha($request->dia_fallecimiento, $request->mes_fallecimiento, $request->anno_fallecimiento);
        }
      }

      return $this->save();
    });
  }

  /**
   * Elimina el personaje y sus datos relacionados.
   *
   * @return bool|null
   */
  public function eliminar_personaje()
  {
    return DB::transaction(function () {
      //Borrar fechas relacionadas si existen
      if ($this->nacimiento != 0) {
        Fecha::destroy($this->nacimiento);
      }
      if ($this->fallecimiento != 0) {
        Fecha::destroy($this->fallecimiento);
      }

      //Borrar el retrato físico sin borrar el default
      if ($this->retrato && $this->retrato !== 'default.png') {
        Storage::disk('public')->delete('retratos/' . $this->retrato);
      }

      //Borrar imágenes de Summernote relacionadas
      $imageService = new ImageService();
      $imageService->deleteImagesByOwner('personajes', $this->id);

      //Borrar el personaje
      return $this->delete();
    });
  }

}
