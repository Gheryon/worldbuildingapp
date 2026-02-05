<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
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
    'politica',
    'estructura',
    'sectas',
    'otros',
    'fundacion_id',
    'disolucion_id',
  ];

  protected $casts = [
    'tipo_teismo' => TipoTeismo::class,
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
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Religion
   */
  public static function store_religion($request)
  {
    return DB::transaction(function () use ($request) {
      // Crear registro
      $religion = self::create([
        'nombre' => $request->nombre,
        'lema' => $request->lema,
        'tipo_teismo' => $request->tipo_teismo,
        'estatus_legal' => $request->estatus_legal,
        'deidades' => $request->deidades,
        'escudo' => 'default.png', // Valor temporal, se actualizará después
      ]);

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      $camposRichText = [
        'clase_sacerdotal' => 'clase_sacerdotal',
        'descripcion' => 'descripcion',
        'historia' => 'historia',
        'cosmologia' => 'cosmologia',
        'doctrina' => 'doctrina',
        'sagrado' => 'sagrado',
        'fiestas' => 'fiestas',
        'politica' => 'politica',
        'estructura' => 'estructura',
        'sectas' => 'sectas',
        'otros' => 'otros'
      ];

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          $religion->$columna = $imageService->processSummernoteImages(
            $request->$input,
            "religiones",
            $religion->id
          );
        }
      }

      //Procesar Fechas
      $religion->fundacion_id = Fecha::store_fecha(
        $request->dia_fundacion,
        $request->mes_fundacion,
        $request->anno_fundacion
      );

      $religion->disolucion_id = Fecha::store_fecha(
        $request->dia_disolucion,
        $request->mes_disolucion,
        $request->anno_disolucion
      );

      //Procesar escudo
      $religion->escudo = self::handleEscudoUpload($request);

      $religion->save();

      return $religion;
    });
  }

  /**
   * Actualiza una religión existente en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Religion
   */
  public function update_religion($request)
  {
    return DB::transaction(function () use ($request) {
      // Campos básicos
      $this->fill([
        'nombre' => $request->nombre,
        'lema' => $request->lema,
        'tipo_teismo' => $request->tipo_teismo,
        'estatus_legal' => $request->estatus_legal,
        'deidades' => $request->deidades,
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

      // Procesado de campos de Summernote
      $imageService = new ImageService();
      $camposRichText = [
        'clase_sacerdotal' => 'clase_sacerdotal',
        'descripcion' => 'descripcion',
        'historia' => 'historia',
        'cosmologia' => 'cosmologia',
        'doctrina' => 'doctrina',
        'sagrado' => 'sagrado',
        'fiestas' => 'fiestas',
        'politica' => 'politica',
        'estructura' => 'estructura',
        'sectas' => 'sectas',
        'otros' => 'otros'
      ];

      foreach ($camposRichText as $columna => $input) {
        if ($request->filled($input)) {
          $this->$columna = $imageService->processSummernoteImages(
            $request->$input,
            "religiones",
            $this->id
          );
        }
      }

      //Procesar Fechas, si existe fundacion_id o disolucion_id se actualiza, si no se crea
      if ($this->fundacion_id) {
        Fecha::update_fecha($request->dia_fundacion, $request->mes_fundacion, $request->anno_fundacion, $this->fundacion_id);
      } else {
        $this->fundacion_id = Fecha::store_fecha($request->dia_fundacion, $request->mes_fundacion, $request->anno_fundacion);
      }

      if ($this->disolucion_id) {
        Fecha::update_fecha($request->dia_disolucion, $request->mes_disolucion, $request->anno_disolucion, $this->disolucion_id);
      } else {
        $this->disolucion_id = Fecha::store_fecha($request->dia_disolucion, $request->mes_disolucion, $request->anno_disolucion);
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
