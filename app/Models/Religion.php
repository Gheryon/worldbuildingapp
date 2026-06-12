<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;
use App\Enums\TipoTeismo;

class Religion extends Model
{
  use HasFactory;

  protected $table = 'religiones';
  protected $primaryKey = 'id';

  protected $fillable = [
    'nombre',
    'lema',
    'escudo',
    'tipo_teismo',
    'deidades',
    'estatus_legal',
    'clase_sacerdotal',
    'descripcion',
    'historia',
    'cosmologia',
    'doctrina',
    'sagrado',
    'fiestas',
    'sobrenatural',
    'politica',
    'estructura',
    'sectas',
    'otros',
    'fundacion_id',
    'disolucion_id',
  ];

  protected $casts = [
    'fundacion_id' => 'integer',
    'disolucion_id' => 'integer',
    'tipo_teismo' => TipoTeismo::class,
  ];

  // Mapeo: 'columna_en_db' => 'nombre_input_formulario'
  public static $richTextFields = [
    'clase_sacerdotal' => 'clase_sacerdotal',
    'descripcion' => 'descripcion',
    'historia' => 'historia',
    'cosmologia' => 'cosmologia',
    'doctrina' => 'doctrina',
    'sagrado' => 'sagrado',
    'fiestas' => 'fiestas',
    'sobrenatural' => 'sobrenatural',
    'politica' => 'politica',
    'estructura' => 'estructura',
    'sectas' => 'sectas',
    'otros' => 'otros',
  ];

  /**
   * Obtiene la información de la fecha de fundación (tabla fechas).
   */
  public function fecha_fundacion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fundacion_id');
  }

  /**
   * Obtiene la información de la fecha de disolución (tabla fechas).
   */
  public function fecha_disolucion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'disolucion_id');
  }

  /**
   * Obtiene los valores del Enum TipoTeismo para selectores.
   * * @return array
   */
  public static function getTiposTeismo(): array
  {
    // Usamos mapWithKeys para asignar el valor interno como clave 
    // y el resultado del método label() como valor del array.
    return collect(TipoTeismo::cases())->mapWithKeys(function ($teismo) {
      return [$teismo->value => $teismo->label()];
    })->sort()->toArray();
  }

  /**
   * Scope para filtrar y ordenar organizaciones.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->select(
      'id',
      'nombre',
    )
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('religiones.nombre', 'LIKE', "%{$search}%");
      })
      ->orderBy('religiones.nombre', $filtros['orden'] ?? 'asc');
  }

  /**
   * Almacena una nueva religión en la base de datos.
   *
   * @param array $request
   * @return \App\Models\Religion
   */
  public static function store_religion(array $request)
  {
    return DB::transaction(function () use ($request) {
      // Manejo del escudo
      if (isset($request['escudo']) && $request['escudo'] instanceof \Illuminate\Http\UploadedFile) {
        $path = $request['escudo']->store('escudos', 'public');
        $request['escudo'] = basename($path);
      } else {
        $request['escudo'] = "default.png";
      }
      // Crear registro
      $religion = self::create($request);

      // Procesado de campos Summernote
      $imageService = app(\App\Services\ImageService::class);
      $imageService->processModelRichText($religion, $request, self::$richTextFields);

      //Procesar Fechas. Lo importante es el año, si no hay año no se guarda fecha
      if (!empty($request['anno_fundacion'])) {
        $religion->fundacion_id = Fecha::sync(null, [
          'dia'  => $request['dia_fundacion'] ?? null,
          'mes'  => $request['mes_fundacion'] ?? null,
          'anno' => $request['anno_fundacion'] ?? null
        ]);
      }

      if (!empty($request['anno_disolucion'])) {
        $religion->disolucion_id = Fecha::sync(null, [
          'dia'  => $request['dia_disolucion'] ?? null,
          'mes'  => $request['mes_disolucion'] ?? null,
          'anno' => $request['anno_disolucion'] ?? null
        ]);
      }

      $religion->save();

      return $religion;
    });
  }

  /**
   * Actualiza una religión existente en la base de datos.
   *
   * @param array $request
   * @return \App\Models\Religion
   */
  public function update_religion(array $request)
  {
    return DB::transaction(function () use ($request) {
      // Campos básicos
      $this->fill($request);

      //Manejo del escudo (Solo si se sube uno nuevo)
      if (isset($request['escudo']) && $request['escudo'] instanceof \Illuminate\Http\UploadedFile) {
        // Borrar escudo anterior si no es el default
        if ($this->escudo !== 'default.png') {
          $oldPath = public_path('storage/escudos/' . $this->escudo);
          if (file_exists($oldPath)) unlink($oldPath);
        }
        $path = $request['escudo']->store('escudos', 'public');
        $request['escudo'] = basename($path);
      }

      // Procesado de campos Summernote
      $imageService = app(\App\Services\ImageService::class);
      $imageService->processModelRichText($this, $request, self::$richTextFields);

      //Actualizado de fechas
      //Procesar Fechas, si existe fundacion_id o disolucion_id se actualiza, si no se crea. Si no hay año no se guarda fecha
      if (!empty($request['anno_fundacion'])) {
        $this->fundacion_id = Fecha::sync($this->fundacion_id, [
          'dia'  => $request['dia_fundacion'] ?? null,
          'mes'  => $request['mes_fundacion'] ?? null,
          'anno' => $request['anno_fundacion'] ?? null
        ]);
      }

      if (!empty($request['anno_disolucion'])) {
        $this->disolucion_id = Fecha::sync($this->disolucion_id, [
          'dia'  => $request['dia_disolucion'] ?? null,
          'mes'  => $request['mes_disolucion'] ?? null,
          'anno' => $request['anno_disolucion'] ?? null
        ]);
      }

      return $this->save();
    });
  }

  /**
   * Maneja la subida del escudo de la religión.
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

  protected static function booted(): void
  {
    static::deleting(function (Religion $religion) {
      $imageService = new ImageService();
      $imageService->deleteImagesByOwner('religiones', $religion->id);

      if ($religion->escudo && $religion->escudo !== 'default.png') {
        Storage::disk('public')->delete('escudos/' . $religion->escudo);
      }

      if ($religion->fundacion_id) {
        Fecha::destroy($religion->fundacion_id);
      }
      if ($religion->disolucion_id) {
        Fecha::destroy($religion->disolucion_id);
      }
    });
  }

  /**
   * Elimina la religión y sus recursos asociados (imágenes).
   * * @return void
   */
  public function delete_religion()
  {
    return DB::transaction(function () {
      //Borrar imágenes de Summernote usando el servicio
      $imageService = new ImageService();
      $imageService->deleteImagesByOwner('religiones', $this->id);

      //Borrar el escudo físico sin borrar el default
      if ($this->escudo && $this->escudo !== 'default.png') {
        Storage::disk('public')->delete('escudos/' . $this->escudo);
      }

      //eliminar fechas
      if ($this->fundacion_id) {
        Fecha::destroy($this->fundacion_id);
      }
      if ($this->disolucion_id) {
        Fecha::destroy($this->disolucion_id);
      }

      // Eliminar la religion
      return $this->delete();
    });
  }
}
