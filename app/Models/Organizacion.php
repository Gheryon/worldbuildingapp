<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\ImageService;

class Organizacion extends Model
{
  use HasFactory;

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
    return $this->belongsTo(tipo_organizacion::class, 'tipo_organizacion_id');
  }

  /**
   * Relación con el personaje que gobierna (lider).
   */
  public function lider(): BelongsTo
  {
    return $this->belongsTo(personaje::class, 'lider_id');
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
      ->select(
        'organizaciones.id',
        'organizaciones.nombre',
        'organizaciones.escudo',
        'organizaciones.tipo_organizacion_id',
        DB::raw('COALESCE(tipo_organizacion.nombre, "Tipo de organización desconocido") as tipo')
      )
      ->where('organizaciones.id', '!=', 0)
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('organizaciones.nombre', 'LIKE', "%{$search}%");
      })
      ->when($filtros['tipo'] ?? null, function ($q, $tipo) {
        if ($tipo > 0) $q->where('organizaciones.tipo_organizacion_id', $tipo);
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
        'capital_nombre' => $request->capital,
        'lema' => $request->lema,
        'lider_id' => $request->select_lider,
        'organizacion_padre_id' => $request->select_organizacion_padre,
        'tipo_organizacion_id' => $request->select_tipo,
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
            $request->$input,
            "organizaciones",
            $organizacion->id
          );
        }
      }

      //Procesar Fechas. Lo importante es el año, si no hay año no se guarda fecha
      if ($request->filled('anno_fundacion')) {
        $organizacion->fundacion_id = Fecha::store_fecha(
          $request->dia_fundacion,
          $request->mes_fundacion,
          $request->anno_fundacion
        );
      }

      if ($request->filled('anno_disolucion')) {
        $organizacion->disolucion_id = Fecha::store_fecha(
          $request->dia_disolucion,
          $request->mes_disolucion,
          $request->anno_disolucion
        );
      }

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
        'capital_nombre'              => $request->capital,
        'lema'                 => $request->lema,
        'lider_id'             => $request->select_lider,
        'organizacion_padre_id' => $request->select_organizacion_padre,
        'tipo_organizacion_id' => $request->select_tipo,
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
}
