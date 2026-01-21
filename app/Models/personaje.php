<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\ConfigurationController;

class personaje extends Model
{
  use HasFactory;

  protected $table = 'personaje';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'nombre_familia',
    'apellidos',
    'lugar_nacimiento',
    'nacimiento',
    'fallecimiento',
    'causa_fallecimiento',
    'descripcion',
    'descripcion_short',
    'personalidad',
    'salud',
    'deseos',
    'miedos',
    'magia',
    'educacion',
    'historia',
    'religion',
    'familia',
    'politica',
    'retrato',
    'id_foranea_especie',
    'sexo',
    'otros'
  ];

  protected $casts = [
    'nacimiento' => 'integer',
    'fallecimiento' => 'integer',
    'lugar_nacimiento' => 'integer',
    'id_foranea_especie' => 'integer',
  ];

  /**
   * Obtiene la especie a la que pertenece el personaje.
   */
  public function especie(): BelongsTo
  {
    return $this->belongsTo(Especie::class, 'id_foranea_especie', 'id');
  }

  /**
   * Obtiene la información de la fecha de nacimiento (tabla fechas).
   */
  public function fechaNacimiento(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'nacimiento', 'id');
  }

  /**
   * Obtiene la información de la fecha de fallecimiento (tabla fechas).
   */
  public function fechaFallecimiento(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fallecimiento', 'id');
  }

  /**
   * Obtiene las organizaciones en las que este personaje es el gobernante (ruler).
   */
  public function organizacionesGobernadas(): HasMany
  {
    return $this->hasMany(Organizacion::class, 'id_ruler', 'id');
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
      $personaje = new self();

      // Asignación de campos básicos
      $personaje->nombre = $request->nombre;
      $personaje->apellidos = $request->apellidos;
      $personaje->nombre_familia = $request->nombre_familia;
      $personaje->lugar_nacimiento = $request->lugar_nacimiento;
      $personaje->causa_fallecimiento = $request->causa_fallecimiento;
      $personaje->id_foranea_especie = $request->select_especie;
      $personaje->sexo = $request->sexo;

      // Obtener ID de organización necesario para el procesador de imágenes
      $id_org = DB::table('organizaciones')->max('id_organizacion') ?? 0;

      // Procesado de campos de Summernote
      $camposRichText = [
        'descripcion' => 'descripcion',
        'descripcion_short' => 'descripcion_short',
        'salud' => 'salud',
        'personalidad' => 'personalidad',
        'deseos' => 'deseos',
        'miedos' => 'miedos',
        'magia' => 'magia',
        'educacion' => 'educacion',
        'historia' => 'historia',
        'religion' => 'religion',
        'familia' => 'familia',
        'politica' => 'politica',
        'otros' => 'otros'
      ];

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          $personaje->$columna = app(ImagenController::class)
            ->store_for_summernote($request->$input, "personajes", $id_org);
        }
      }

      // Manejo del Retrato
      if ($request->hasFile('retrato')) {
        $path = $request->file('retrato')->store('retratos', 'public');
        $personaje->retrato = basename($path);
      } else {
        $personaje->retrato = "default.png";
      }

      // Manejo de Fechas
      $personaje->nacimiento = app(ConfigurationController::class)->store_fecha(
        $request->input('dnacimiento', 0),
        $request->input('mnacimiento', 0),
        $request->input('anacimiento', 0),
        "personajes"
      );

      $personaje->fallecimiento = app(ConfigurationController::class)->store_fecha(
        $request->input('dfallecimiento', 0),
        $request->input('mfallecimiento', 0),
        $request->input('afallecimiento', 0),
        "personajes"
      );

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
      $this->nombre = $request->nombre;
      $this->apellidos = $request->apellidos;
      $this->nombre_familia = $request->nombre_familia;
      $this->lugar_nacimiento = $request->lugar_nacimiento;
      $this->causa_fallecimiento = $request->causa_fallecimiento;
      $this->id_foranea_especie = $request->select_especie;
      $this->sexo = $request->sexo;

      // Procesado de campos de Summernote
      $camposRichText = [
        'descripcion' => 'descripcion',
        'descripcion_short' => 'descripcion_short',
        'salud' => 'salud',
        'personalidad' => 'personalidad',
        'deseos' => 'deseos',
        'miedos' => 'miedos',
        'magia' => 'magia',
        'educacion' => 'educacion',
        'historia' => 'historia',
        'religion' => 'religion',
        'familia' => 'familia',
        'politica' => 'politica',
        'otros' => 'otros'
      ];

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          $this->$columna = app(ImagenController::class)
            ->store_for_summernote($request->$input, "personajes", $this->id);
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

      // Manejo de Fechas (Delegado al ConfigurationController)
      $config = app(ConfigurationController::class);

      $this->nacimiento = ($request->input('anacimiento', 0) != 0)
        ? ($this->nacimiento != 0 // ya existía una fecha, se actualiza, sino, se añade una nueva
          ? $config->update_fecha($request->dnacimiento, $request->mnacimiento, $request->anacimiento, $this->nacimiento)
          : $config->store_fecha($request->dnacimiento, $request->mnacimiento, $request->anacimiento, "personajes"))
        : $this->nacimiento; //Si no hay datos, se mantiene el actual

      $this->fallecimiento = ($request->input('afallecimiento', 0) != 0)
        ? ($this->fallecimiento != 0 // ya existía una fecha, se actualiza, sino, se añade una nueva
          ? $config->update_fecha($request->dfallecimiento, $request->mfallecimiento, $request->afallecimiento, $this->fallecimiento)
          : $config->store_fecha($request->dfallecimiento, $request->mfallecimiento, $request->afallecimiento, "personajes"))
        : $this->fallecimiento; //Si no hay datos, se mantiene el actual

      return $this->save();
    });
  }

  /**
   * Obtiene los personajes con id diferente de 0, ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_personajes($orden = 'asc', $tipo = '0', $perPage = 18)
  {
    try {
      // Validar que el orden sea un valor permitido para evitar inyecciones
      $direccionesValidas = ['asc', 'desc'];
      $dir = in_array(strtolower($orden), $direccionesValidas) ? $orden : 'asc';

      // Usamos leftJoin para no excluir personajes si la especie no existe (como el ID 0)
      $query = self::leftJoin('especies', 'personaje.id_foranea_especie', '=', 'especies.id')
        ->select(
          'personaje.id',
          'personaje.nombre',
          'personaje.retrato',
          'personaje.sexo',
          'personaje.id_foranea_especie',
          // Si el nombre de la especie es nulo (caso ID 0), devolvemos 'Sistema/Desconocido'
          DB::raw('COALESCE(especies.nombre, "Especie desconocida") as especie')
        )->where('personaje.id', '!=', 0);

      // Si tipo es 0, devuelve todos. Si es > 0, se filtra por especie.
      if ($tipo > 0) {
        $query->where('personaje.id_foranea_especie', $tipo);
      }

      return $query->orderBy('personaje.nombre', $dir)->paginate($perPage);
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al obtener la lista de personajes.",
        [
          'orden' => $orden,
          'tipo' => $tipo,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      // Devolvemos una colección vacía para que la aplicación no se rompa.
      return collect();
    } catch (\Exception $e) {
      // Captura cualquier otra excepción inesperada.
      Log::critical(
        "Error inesperado al obtener la lista de personajes.",
        [
          'orden' => $orden,
          'tipo' => $tipo,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return collect();
    }
  }

  /**
   * Obtiene id y nombre de los personajes con id diferente de 0, ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_personajes_id_nombre()
  {
    try {
      // Intenta ejecutar la consulta y devolver el resultado ordenado.
      return self::select('id', 'Nombre')
        ->where('personaje.id', '!=', 0)->orderBy('Nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        'Error de base de datos al obtener personajes.',
        [
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      // Devuelve una colección vacía para que la aplicación pueda continuar
      return new Collection();
    } catch (\Exception $e) {
      Log::error(
        'Error inesperado al obtener los personajes.',
        [
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      //Devuelve una colección vacía como medida de seguridad.
      return new Collection();
    }
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
      $imagenes = DB::table('imagenes')
        ->where('table_owner', 'personajes')
        ->where('owner', $this->id)
        ->get();

      foreach ($imagenes as $imagen) {
        $rutaCompleta = public_path("storage/imagenes/" . $imagen->nombre);
        if (file_exists($rutaCompleta)) {
          unlink($rutaCompleta);
        }
        DB::table('imagenes')->where('id', $imagen->id)->delete();
      }

      //Borrar el personaje
      return $this->delete();
    });
  }

  /**
   * Scope para filtrar y ordenar personajes.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->leftJoin('especies', 'personaje.id_foranea_especie', '=', 'especies.id')
      ->select(
        'personaje.id',
        'personaje.nombre',
        'personaje.retrato',
        'personaje.sexo',
        'personaje.id_foranea_especie',
        DB::raw('COALESCE(especies.nombre, "Especie desconocida") as especie')
      )
      ->where('personaje.id', '!=', 0)
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('personaje.nombre', 'LIKE', "%{$search}%");
      })
      ->when($filtros['especie'] ?? null, function ($q, $especie) {
        if ($especie > 0) $q->where('personaje.id_foranea_especie', $especie);
      })
      ->orderBy('personaje.nombre', $filtros['orden'] ?? 'asc');
  }
}
