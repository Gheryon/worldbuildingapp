<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Traits\HandlesRichTextImages;

class Conflicto extends Model
{
  use HasFactory, HandlesRichTextImages;

  protected $table = 'conflictos';
  protected $primaryKey = 'id';
  public $timestamps = true;

  protected $fillable = [
    'nombre',
    'fecha_inicio_id',
    'fecha_fin_id',
    'ubicacion_principal_type',
    'ubicacion_principal_id',
    'tipo_localizacion',
    'conflicto_padre_id',
    'tipo_conflicto_id',
    'vencedor_texto',
    'descripcion',
    'preludio',
    'desarrollo',
    'resultado',
    'consecuencias',
    'otros',
    'fenomenos_naturales',
    'es_conflicto_magico',
    'seres_sobrenaturales_participantes',
    'armas_magicas_empleadas',
    'hechizos_decisivos',
    'unidades_especiales',
    'criaturas_combate',
    'maquinaria_warlike',
  ];

  protected $casts = [
    'es_conflicto_magico' => 'boolean',
    'fecha_inicio_id' => 'integer',
    'fecha_fin_id' => 'integer',
    'tipo_conflicto_id' => 'integer',
    'conflicto_padre_id' => 'integer',
  ];

  // Mapeo: 'columna_en_db' => 'nombre_input_formulario'
  public static $richTextFields = [
    'descripcion'                         => 'descripcion',
    'preludio'                            => 'preludio',
    'desarrollo'                          => 'desarrollo',
    'unidades_especiales'                 => 'unidades_especiales',
    'criaturas_combate'                   => 'criaturas_combate',
    'maquinaria_warlike'                  => 'maquinaria_warlike',
    'hechizos_decisivos'                  => 'hechizos_decisivos',
    'armas_magicas_empleadas'             => 'armas_magicas_empleadas',
    'seres_sobrenaturales_participantes'  => 'seres_sobrenaturales_participantes',
    'fenomenos_naturales'                 => 'fenomenos_naturales',
    'resultado'                           => 'resultado',
    'consecuencias'                       => 'consecuencias',
    'otros'                               => 'otros'
  ];

  // --- Relaciones ---
  public function fechaInicio(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fecha_inicio_id');
  }

  public function fechaFin(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fecha_fin_id');
  }

  public function ubicacionPrincipal(): MorphTo
  {
    return $this->morphTo();
  }

  public function conflictoPadre(): BelongsTo
  {
    return $this->belongsTo(Conflicto::class, 'conflicto_padre_id');
  }

  public function tipoConflicto(): BelongsTo
  {
    return $this->belongsTo(TipoConflicto::class, 'tipo_conflicto_id');
  }

  public function personajes()
  {
    return $this->belongsToMany(Personaje::class, 'beligerantes_personajes')
      ->withPivot('lado', 'es_vencedor')
      ->withTimestamps();
  }

  /**
   * Obtiene los personajes presentes en un determinado bando (lado).
   * * @param string $lado
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getPersonajesPorBando(string $lado)
  {
    return $this->personajes()
      ->wherePivot('lado', $lado)
      ->get();
  }

  /**
   * Obtiene específicamente a los personajes que resultaron vencedores.
   */
  public function getPersonajesVencedores()
  {
    return $this->personajes()
      ->wherePivot('es_vencedor', true)
      ->get();
  }

  /**
   * Relación de muchos a muchos con Organizaciones.
   * Permite acceder a los bandos y ganadores a través de la tabla pivote.
   */
  public function organizaciones()
  {
    return $this->belongsToMany(Organizacion::class, 'beligerantes_organizaciones')
      ->withPivot('lado', 'es_vencedor')
      ->withTimestamps();
  }

  /**
   * Obtiene las organizaciones presentes en un conflicto en un determinado bando.
   * * @param string $lado
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function getOrganizacionesPorBando(string $lado)
  {
    return $this->organizaciones()
      ->wherePivot('lado', $lado)
      ->get();
  }

  /**
   * Scope para filtrar y ordenar conflictos.
   */
  public function scopeFiltrar($query, $filtros)
  {
    return $query->with('tipoConflicto')
      // Si 'tipo' tiene un valor (y no es 0 o vacío), se filtra.
      // PHP interpreta 0 como false
      ->when(!empty($filtros['tipo']), function ($q) use ($filtros) {
        $q->where('tipo_conflicto_id', $filtros['tipo']);
      })
      ->when(isset($filtros['magia']) && $filtros['magia'] != 0, function ($q) use ($filtros) {
        // Si magia es 1, se busca true (1). Si es 2, false (0).
        $valorBusqueda = ($filtros['magia'] == 1);
        $q->where('es_conflicto_magico', $valorBusqueda);
      })
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('nombre', 'LIKE', "%{$search}%");
      })
      ->orderBy('nombre', $filtros['orden'] ?? 'asc');
  }

  /**
   * Almacena un nuevo conflicto y sus relaciones asociadas.
   * * @param array $datos
   * @return \App\Models\Conflicto
   */
  public static function store_conflicto(array $datos)
  {
    return DB::transaction(function () use ($datos) {
      $conflicto = self::create($datos);

      //Manejo de Checkboxes, en un array, si el checkbox no se marcó, la clave no existe.
      $conflicto->es_conflicto_magico = isset($datos['es_conflicto_magico']);

      // Procesado campos RichText (Summernote)
      $conflicto->processRichTextImages($datos, self::$richTextFields, 'conflictos');

      //Procesar Fechas. Lo importante es el año, si no hay año no se guarda fecha
      if (!empty($datos['anno_fecha_inicio'])) {
        $conflicto->fecha_inicio_id = Fecha::sync(null, [
          'dia'  => $datos['dia_fecha_inicio'] ?? 0,
          'mes'  => $datos['mes_fecha_inicio'] ?? 0,
          'anno' => $datos['anno_fecha_inicio'] ?? null
        ]);
      }

      if (!empty($datos['anno_fecha_fin'])) {
        $conflicto->fecha_fin_id = Fecha::sync(null, [
          'dia'  => $datos['dia_fecha_fin'] ?? 0,
          'mes'  => $datos['mes_fecha_fin'] ?? 0,
          'anno' => $datos['anno_fecha_fin'] ?? null,
        ]);
      }

      // Sincronizar personajes atacantes
      if (!empty($datos['personajes_atacantes'])) {
        foreach ($datos['personajes_atacantes'] as $id) {
          $conflicto->personajes()->attach($id, [
            'lado' => 'atacante',
            'es_vencedor' => false // Por defecto false, se edita en el 'edit'
          ]);
        }
      }

      // Sincronizar paises atacantes
      if (!empty($datos['paises_atacantes'])) {
        foreach ($datos['paises_atacantes'] as $id) {
          $conflicto->organizaciones()->attach($id, [
            'lado' => 'atacante',
            'es_vencedor' => false // Por defecto false, se edita en el 'edit'
          ]);
        }
      }

      // Sincronizar personajes defensores
      if (!empty($datos['personajes_defensores'])) {
        foreach ($datos['personajes_defensores'] as $id) {
          $conflicto->personajes()->attach($id, [
            'lado' => 'defensor',
            'es_vencedor' => false
          ]);
        }
      }

      // Sincronizar paises defensores
      if (!empty($datos['paises_defensores'])) {
        foreach ($datos['paises_defensores'] as $id) {
          $conflicto->organizaciones()->attach($id, [
            'lado' => 'defensor',
            'es_vencedor' => false // Por defecto false, se edita en el 'edit'
          ]);
        }
      }

      $conflicto->save();

      return $conflicto;
    });
  }

  /**
   * Actualiza un conflicto existente en la base de datos.
   *
   * @param \Illuminate\Http\Request $request
   * @return \App\Models\Conflicto
   */
  public function update_conflicto(array $datos)
  {
    return DB::transaction(function () use ($datos) {
      //Campos básicos
      $this->fill($datos);

      //Manejo de Checkboxes, en un array, si el checkbox no se marcó, la clave no existe.
      $this->es_conflicto_magico = isset($datos['es_conflicto_magico']);

      // Procesado campos RichText (Summernote)
      $this->processRichTextImages($datos, self::$richTextFields, 'conflictos');

      //Actualizado de fechas
      //Procesar Fechas, si existe *_id se actualiza, si no se crea. Si no hay año no se guarda fecha
      if (!empty($datos['anno_fecha_inicio'])) {
        $this->fecha_inicio_id = Fecha::sync($this->fecha_inicio_id, [
          'dia'  => $datos['dia_fecha_inicio'] ?? 0,
          'mes'  => $datos['mes_fecha_inicio'] ?? 0,
          'anno' => $datos['anno_fecha_inicio'] ?? null
        ]);
      }

      if (!empty($datos['anno_fecha_fin'])) {
        $this->fecha_fin_id = Fecha::sync($this->fecha_fin_id, [
          'dia'  => $datos['dia_fecha_fin'] ?? 0,
          'mes'  => $datos['mes_fecha_fin'] ?? 0,
          'anno' => $datos['anno_fecha_fin'] ?? null,
        ]);
      }

      /**Sincronización de personajes y organizaciones**/
      $bandoGanador = $datos['bando_vencedor'] ?? 'ninguno';
      //Se quitan los que ya estaban en ese bando para no duplicar, no sirve el método sync usado en organizaciones con religiones
      //porque hay que indicar el campo 'lado' de la tabla pivote, así que se hace a mano.
      // Sincronizar personajes atacantes
      $this->personajes()->wherePivot('lado', 'atacante')->detach();
      if (!empty($datos['personajes_atacantes'])) {
        foreach ($datos['personajes_atacantes'] as $id) {
          $this->personajes()->attach($id, ['lado' => 'atacante', 'es_vencedor' => ($bandoGanador === 'atacante')]);
        }
      }

      // Sincronizar paises atacantes
      $this->organizaciones()->wherePivot('lado', 'atacante')->detach();
      if (!empty($datos['paises_atacantes'])) {
        foreach ($datos['paises_atacantes'] as $id) {
          $this->organizaciones()->attach($id, ['lado' => 'atacante', 'es_vencedor' => ($bandoGanador === 'atacante')]);
        }
      }

      // Sincronizar personajes defensores
      $this->personajes()->wherePivot('lado', 'defensor')->detach();
      if (!empty($datos['personajes_defensores'])) {
        foreach ($datos['personajes_defensores'] as $id) {
          $this->personajes()->attach($id, ['lado' => 'defensor', 'es_vencedor' => ($bandoGanador === 'defensor')]);
        }
      }

      // Sincronizar paises defensores
      $this->organizaciones()->wherePivot('lado', 'defensor')->detach();
      if (!empty($datos['paises_defensores'])) {
        foreach ($datos['paises_defensores'] as $id) {
          $this->organizaciones()->attach($id, ['lado' => 'defensor', 'es_vencedor' => ($bandoGanador === 'defensor')]);
        }
      }

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
    static::deleting(function ($conflicto) {
      // Llamamos al servicio para limpiar el disco y la DB
      //$imageService = new \App\Services\ImageService();
      //$imageService->deleteImagesByOwner('conflictos', $conflicto->id);
      //Versión alternativa con service container, para evitar inyección directa y facilitar testing/mocking
      app(\App\Services\ImageService::class)->deleteImagesByOwner('conflictos', $conflicto->id);

      //Borrado de fechas
      if ($conflicto->fecha_inicio_id) {
        \App\Models\Fecha::destroy($conflicto->fecha_inicio_id);
      }

      if ($conflicto->fecha_fin_id) {
        \App\Models\Fecha::destroy($conflicto->fecha_fin_id);
      }

      //Borrado de relaciones con personajes y organizaciones
      $conflicto->personajes()->detach();
      $conflicto->organizaciones()->detach();
    });
  }
}
