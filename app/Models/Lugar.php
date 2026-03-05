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

  /**
   * Relación con el tipo de lugar.
   */
  public function tipo(): BelongsTo
  {
    return $this->belongsTo(tipo_lugar::class, 'tipo_lugar_id');
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
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Lugar
   */
  public static function store_lugar($request)
  {
    return DB::transaction(function () use ($request) {
      $lugar = self::create([
        'nombre'            => $request->nombre,
        'otros_nombres'     => $request->otros_nombres,
        'tipo_lugar_id'     => $request->select_tipo,
        'nivel_peligro'     => $request->nivel_peligro,
        'tipo_peligro'      => $request->tipo_peligro,
        'dificultad_acceso' => $request->dificultad_acceso,
        'estacionalidad'    => $request->estacionalidad,
        'es_secreto'        => $request->has('es_secreto')
      ]);

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      // Mapeo: 'columna_en_db' => 'nombre_input_formulario'
      $camposRichText = [
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

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          $lugar->$columna = $imageService->processSummernoteImages(
            $request->$input,
            "lugares",
            $lugar->id
          );
        }
      }

      // Guardamos los cambios finales (rutas de imágenes y fechas)
      $lugar->save();

      return $lugar;
    });
  }

  /**
   * Actualiza un lugar existente en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Lugar
   */
  public function update_lugar($request)
  {
    return DB::transaction(function () use ($request) {
      //Campos básicos
      $this->fill([
        'nombre'            => $request->nombre,
        'otros_nombres'     => $request->otros_nombres,
        'tipo_lugar_id'     => $request->select_tipo,
        'nivel_peligro'     => $request->nivel_peligro,
        'tipo_peligro'      => $request->tipo_peligro,
        'dificultad_acceso' => $request->dificultad_acceso,
        'estacionalidad'    => $request->estacionalidad,
        'es_secreto'        => $request->has('es_secreto')
      ]);

      // Procesado campos RichText (Summernote)
      $imageService = new ImageService();
      $campos = [
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

      foreach ($campos as $campo) {
        if ($request->filled($campo)) {
          $this->$campo = $imageService->processSummernoteImages(
            $request->$campo,
            "organizaciones",
            $this->id
          );
        }
      }

      return $this->save();
    });
  }

  /**
   * Elimina el lugar y todos sus recursos asociados (archivos y registros).
   *
   */
  public function delete_lugar()
  {
    return DB::transaction(function () {
      //Borrar imágenes de Summernote usando el servicio
      $imageService = new ImageService();
      $imageService->deleteImagesByOwner('lugares', $this->id);

      //Eliminar el lugar
      return $this->delete();
    });
  }
}
