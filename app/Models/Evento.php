<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HandlesRichTextImages;
use Illuminate\Support\Facades\DB;

class Evento extends Model
{
  use HasFactory, HandlesRichTextImages;

  protected $table = 'eventos';
  protected $primaryKey = 'id';
  public $timestamps = true;

  protected $fillable = ['nombre', 'descripcion', 'fecha_id', 'tipo', 'categoria'];

  protected $casts = [
    'fecha_id' => 'integer',
  ];

  // Mapeo: 'columna_en_db' => 'nombre_input_formulario'
  public static $richTextFields = [
    'descripcion' => 'descripcion',
  ];

  // Relación con modelo Fecha
  public function fecha(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fecha_id');
  }

  // Scope para filtrar por tipo de forma limpia
  public function scopeOfType($query, $type)
  {
    return $query->where('tipo', $type);
  }

  // Scope para ordenar cronológicamente usando la relación Fecha
  public function scopeFiltrar($query, $filters)
  {
    $direction = (isset($filters['orden']) && strtolower($filters['orden']) === 'asc') ? 'desc' : 'asc';
    return $query->join('fechas', 'eventos.fecha_id', '=', 'fechas.id')
      ->orderBy('fechas.anno', $direction)
      ->orderBy('fechas.mes', $direction)
      ->orderBy('fechas.dia', $direction)
      ->select('eventos.*');
  }

  /**
   * Crea una instancia de Evento basada en la fecha del mundo.
   */
  public static function get_evento_fecha_mundo()
  {
    $fechaMundo = Fecha::find(1);
    if (!$fechaMundo) return null;

    // Creamos un objeto Evento al vuelo (sin guardar en DB)
    $evento = new self([
      'nombre' => 'Fecha Actual del Mundo',
      'descripcion' => 'Punto cronológico actual.',
      'tipo' => 'fecha_actual',
    ]);

    // Asignamos la relación manualmente
    $evento->setRelation('fecha', $fechaMundo);
    $evento->id = 0; // ID ficticio para evitar conflictos

    return $evento;
  }

  /**
   * Almacena un nuevo evento en la base de datos.
   *
   * @param array $request
   * @return \App\Models\Evento
   */
  public static function store_evento(array $request)
  {
    return DB::transaction(function () use ($request) {

      $evento = self::create($request);

      // Procesado de campos de Summernote
      $evento->processRichTextImages($request, self::$richTextFields, 'eventos');

      $evento->tipo = $request['form_tipo'] ?? 'general';
      $evento->categoria = $request['form_categoria'] ?? 'local';

      //Procesar Fecha. Lo importante es el año, si no hay año no se guarda fecha
      if (!empty($request['anno'])) {
        $evento->fecha_id = Fecha::sync(null, [
          'dia'  => $request['dia'] ?? null,
          'mes'  => $request['mes'] ?? null,
          'anno' => $request['anno'] ?? null
        ]);
      }

      $evento->save();

      return $evento;
    });
  }

  /**
   * Actualiza un evento existente y su fecha relacionada.
   *
   * @param array $data Datos provenientes del request
   * @return bool
   */
  public function update_evento(array $data)
  {
    return DB::transaction(function () use ($data) {
      //Campos básicos
      $this->fill($data);

      //Procesar imágenes de Summernote
      $this->processRichTextImages($data, self::$richTextFields, 'eventos');

      $this->tipo = $data['form_tipo'] ?? 'general';
      $this->categoria = $data['form_categoria'] ?? 'local';

      //Actualizar la fecha relacionada
      if ($this->fecha_id) {
        $fecha = Fecha::find($this->fecha_id);
        if ($fecha) {
          $fecha->update([
            'dia'  => $data['dia'] ?? null,
            'mes'  => $data['mes'] ?? null,
            'anno' => $data['anno'],
          ]);
        }
      }

      //Actualizar los campos del evento
      return $this->save();
    });
  }

  /**
   * Elimina la construcción y sus recursos relacionados (imágenes y fechas).
   *
   * @return bool|null
   * @throws \Exception
   */
  protected static function booted()
  {
    static::deleting(function ($evento) {
      // Llamamos al servicio para limpiar el disco y la DB
      //Versión alternativa con service container, para evitar inyección directa y facilitar testing/mocking
      app(\App\Services\ImageService::class)->deleteImagesByOwner('conflictos', $evento->id);

      //Borrado de fechas
      if ($evento->fecha_id) {
        \App\Models\Fecha::destroy($evento->fecha_id);
      }
    });
  }
}
