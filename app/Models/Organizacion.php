<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Services\ImageService;

class Organizacion extends Model
{
  use HasFactory;

  protected $table = 'organizaciones';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'gentilicio',
    'capital',
    'escudo',
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
    'id_ruler',
    'id_owner',
    'id_tipo_organizacion',
    'fundacion',
    'disolucion',
  ];

  protected $casts = [
    'fundacion' => 'integer',
    'disolucion' => 'integer',
    'id_ruler' => 'integer',
    'id_owner' => 'integer',
    'id_tipo_organizacion' => 'integer',
  ];

  /**
   * Obtiene la información de la fecha de fundación (tabla fechas).
   */
  public function fecha_fundacion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fundacion', 'id');
  }

  /**
   * Obtiene la información de la fecha de disolución (tabla fechas).
   */
  public function fecha_disolucion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'disolucion', 'id');
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
    return $this->belongsTo(tipo_organizacion::class, 'id_tipo_organizacion');
  }

  /**
   * Relación con el personaje que gobierna (ruler).
   */
  public function ruler(): BelongsTo
  {
    return $this->belongsTo(personaje::class, 'id_ruler');
  }

  /**
   * Relación con la organización de la que depende (owner).
   */
  public function owner(): BelongsTo
  {
    return $this->belongsTo(Organizacion::class, 'id_owner');
  }

  /**
   * Relación con las organizaciones que dependen de esta (hijas).
   */
  public function subordinates(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany(Organizacion::class, 'id_owner', 'id');
  }

  /**
   * Scope para filtrar y ordenar organizaciones.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->leftJoin('tipo_organizacion', 'organizaciones.id_tipo_organizacion', '=', 'tipo_organizacion.id')
      ->select(
        'organizaciones.id',
        'organizaciones.nombre',
        'organizaciones.descripcion_breve',
        'organizaciones.escudo',
        'organizaciones.id_tipo_organizacion',
        DB::raw('COALESCE(tipo_organizacion.nombre, "Tipo de organización desconocido") as tipo')
      )
      ->where('organizaciones.id', '!=', 0)
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('organizaciones.nombre', 'LIKE', "%{$search}%");
      })
      ->when($filtros['tipo'] ?? null, function ($q, $tipo) {
        if ($tipo > 0) $q->where('organizaciones.id_tipo_organizacion', $tipo);
      })
      ->orderBy('organizaciones.nombre', $filtros['orden'] ?? 'asc');
  }

  /**
   * Almacena una nueva organización en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Organizacion
   */
  public static function store_organizacion($request)
  {
    return DB::transaction(function () use ($request) {
      $organizacion = self::create([
        'nombre' => $request->nombre,
        'gentilicio' => $request->gentilicio,
        'capital' => $request->capital,
        'lema' => $request->lema,
        'id_ruler' => $request->select_ruler,
        'id_owner' => $request->select_owner,
        'id_tipo_organizacion' => $request->select_tipo,
        'escudo' => 'default.png', // Valor temporal, se actualizará después
      ]);

      // Manejo de la subida del escudo
      $organizacion->escudo = self::handleEscudoUpload($request);

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      $camposRichText = [
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
        'otros' => 'otros'
      ];

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          //$organizacion->$columna = app(ImagenController::class)
          //->store_for_summernote($request->$input, "organizaciones", $organizacion->id);
          $organizacion->$columna = $imageService->processSummernoteImages(
            $request->$columna,
            "organizaciones",
            $organizacion->id
          );
        }
      }

      // Manejo de Fechas
      $organizacion->fundacion = Fecha::store_fecha(
        $request->input('dfundacion', 0),
        $request->input('mfundacion', 0),
        $request->input('afundacion', 0),
        'organizaciones'
      );

      $organizacion->disolucion = Fecha::store_fecha(
        $request->input('ddisolucion', 0),
        $request->input('mdisolucion', 0),
        $request->input('adisolucion', 0),
        'organizaciones'
      );

      // Guardado de Religiones (Tabla pivote)
      if ($request->filled('religiones')) {
        /*foreach ($request->input('religiones') as $religionId) {
          DB::table('religion_presence')->insert([
            'organizacion' => $organizacion->id,
            'religion'     => $religionId
          ]);
        }*/
        $organizacion->religiones()->attach($request->religiones);
      }

      // Guardamos los cambios finales (rutas de imágenes y fechas)
      $organizacion->save();

      return $organizacion;
    });
  }

  /**
   * Actualiza una organización existente en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Organizacion
   */
  public function update_organizacion($request)
  {
    return DB::transaction(function () use ($request) {
      //Campos básicos
      $this->fill([
        'nombre'               => $request->nombre,
        'gentilicio'           => $request->gentilicio,
        'capital'              => $request->capital,
        'lema'                 => $request->lema,
        'id_ruler'             => $request->select_ruler,
        'id_owner'             => $request->select_owner,
        'id_tipo_organizacion' => $request->select_tipo,
      ]);

      //Manejo del escudo (Solo si se sube uno nuevo)
      if ($request->hasFile('escudo')) {
        // Borrar escudo anterior si no es el default
        if ($this->escudo !== 'default.png') {
          $oldPath = public_path('storage/escudos/' . $this->escudo);
          if (file_exists($oldPath)) unlink($oldPath);
        }
        $this->escudo = self::handleEscudoUpload($request);
      }

      // Procesado campos RichText (Summernote)
      $imageService = new ImageService();
      $campos = [
        'demografia',
        'descripcion_breve',
        'estructura',
        'geopolitica',
        'militar',
        'cultura',
        'tecnologia',
        'educacion',
        'historia',
        'religion',
        'territorio',
        'economia',
        'recursos_naturales',
        'otros'
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
      $this->fundacion = Fecha::store_fecha(
        $request->input('dfundacion', 0),
        $request->input('mfundacion', 0),
        $request->input('afundacion', 0),
        'organizaciones'
      );

      $this->disolucion = Fecha::store_fecha(
        $request->input('ddisolucion', 0),
        $request->input('mdisolucion', 0),
        $request->input('adisolucion', 0),
        'organizaciones'
      );

      //Sincronizar religiones
      $this->religiones()->sync($request->input('religiones', []));

      return $this->save();
    });
  }

  /**
   * Maneja la subida del escudo de la organización.
   *
   * @param \Illuminate\Http\Request $request
   * @return string Nombre del archivo subido o "default.png" si no se subió ningún archivo.
   */
  private static function handleEscudoUpload($request)
  {
    if ($request->hasFile('escudo')) {
      $file = $request->file('escudo');

      // Generamos un nombre único para evitar que se sobrescriban
      $nombreArchivo = time() . '_' . $file->getClientOriginalName();

      // Mover directamente a public/storage/escudos para que sea accesible vía URL y por el sistema de archivos
      $file->move(public_path('storage/escudos'), $nombreArchivo);

      return $nombreArchivo;
    }
    return "default.png";
  }


  /**
   * Elimina la organización y todos sus recursos asociados (archivos y registros).
   *
   */
  public function delete_organizacion()
  {
    return DB::transaction(function () {
      //Borrar fechas asociadas
      if ($this->fundacion != 0) {
        Fecha::destroy($this->fundacion);
      }
      if ($this->disolucion != 0) {
        Fecha::destroy($this->disolucion);
      }

      //Borrar escudo si no es el por defecto
      if ($this->escudo !== 'default.png') {
        $pathEscudo = public_path('storage/escudos/' . $this->escudo);
        if (file_exists($pathEscudo)) {
          unlink($pathEscudo);
        }
      }

      //Borrar imágenes de Summernote usando el servicio
      $imageService = new ImageService();
      $imageService->deleteImagesByOwner('organizaciones', $this->id);

      //Borrar relación con religiones (tabla pivote)
      $this->religiones()->detach();

      //Eliminar la organización
      return $this->delete();
    });
  }

  /**
   * Obtiene id y nombre de las organizaciones con id diferente de 0, ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_organizaciones_id_nombre()
  {
    try {
      // Intenta ejecutar la consulta y devolver el resultado ordenado.
      return self::select('id', 'nombre')
        ->where('organizaciones.id', '!=', 0)
        ->orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        'Error de base de datos al obtener organizaciones.',
        [
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      // Devuelve una colección vacía para que la aplicación pueda continuar
      return new Collection();
    } catch (\Exception $e) {
      Log::error(
        'Error inesperado al obtener organizaciones.',
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
   * Obtiene los id de las religiones presentes en una organizacion en un determinado bando.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_religiones_presentes($id)
  {
    try {
      $religiones = DB::table('religion_presence')->select('religion')->where('organizacion', '=', $id)->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('Organizacion->get_religiones_presentes: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $religiones = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error('Organizacion->get_religiones_presentes: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $religiones = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $religiones;
  }

  /**
   * Obtiene una organización por su ID.
   *
   * @param int $id
   * @return \App\Models\organizacion|null
   */
  public static function get_organizacion($id)
  {
    try {
      $organizacion = self::findorfail($id);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      Log::error("organizacion->get_organizacion: Organización no encontrada con ID: " . $id);
      $organizacion = ['error' => ['error' => "Organización no encontrada con ID: " . $id]];
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error("organizacion->get_organizacion: Error de base de datos al obtener organización con ID: " . $id . " - " . $excepcion->getMessage());
      $organizacion = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (\Exception $excepcion) {
      Log::error("organizacion->get_organizacion: Error general al obtener organización con ID: " . $id . " - " . $excepcion->getMessage());
      $organizacion = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return $organizacion;
  }
}
