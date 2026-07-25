<?php

namespace App\Models;

use App\Models\Traits\HasReferenceImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Cultura extends Model
{
  use HasFactory, HasReferenceImages;

  protected $table = 'culturas';

  protected $primaryKey = 'id';

  public $timestamps = true;

  protected $fillable = [
    'categoria',
    'nombre',
    'gentilicio',
    'estatus',
    'tipo_territorio',
    'fundacion_id',
    'disolucion_id',
    'madre_id',
    'descripcion_breve',
    'distribucion_geografica',
    'historia',
    'idioma',
    'estructura_social',
    'roles_genero',
    'unidad_familiar',
    'cosmovision',
    'fiestas',
    'tabues',
    'simbolos',
    'etica',
    'vestimenta',
    'gastronomia',
    'arquitectura',
    'arte_musica',
    'tecnologia',
    'educacion',
    'actitud_magia',
    'actitud_forasteros',
    'otros',
  ];

  protected $casts = [
    'fundacion_id' => 'integer',
    'disolucion_id' => 'integer',
    'madre_id' => 'integer',
  ];

  public static $richTextFields = [
    'descripcion_breve' => 'descripcion_breve',
    'distribucion_geografica' => 'distribucion_geografica',
    'historia' => 'historia',
    'idioma' => 'idioma',
    'estructura_social' => 'estructura_social',
    'roles_genero' => 'roles_genero',
    'cosmovision' => 'cosmovision',
    'fiestas' => 'fiestas',
    'tabues' => 'tabues',
    'simbolos' => 'simbolos',
    'etica' => 'etica',
    'vestimenta' => 'vestimenta',
    'gastronomia' => 'gastronomia',
    'arquitectura' => 'arquitectura',
    'arte_musica' => 'arte_musica',
    'tecnologia' => 'tecnologia',
    'educacion' => 'educacion',
    'actitud_magia' => 'actitud_magia',
    'actitud_forasteros' => 'actitud_forasteros',
    'otros' => 'otros',
  ];

  public function fecha_fundacion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'fundacion_id');
  }

  public function fecha_disolucion(): BelongsTo
  {
    return $this->belongsTo(Fecha::class, 'disolucion_id');
  }

  public function cultura_madre(): BelongsTo
  {
    return $this->belongsTo(Cultura::class, 'madre_id');
  }

  public function culturas_hijas(): \Illuminate\Database\Eloquent\Relations\HasMany
  {
    return $this->hasMany(Cultura::class, 'madre_id', 'id');
  }

  public function scopeFiltrar($query, $filtros)
  {
    return $query
      ->when($filtros['search'] ?? null, function ($q, $search) {
        $q->where('nombre', 'LIKE', "%{$search}%");
      })
      ->when($filtros['estatus'] ?? null, function ($q, $estatus) {
        if ($estatus !== '0') {
          $q->where('estatus', $estatus);
        }
      })
      ->orderBy('nombre', $filtros['orden'] ?? 'asc');
  }

  public static function store_cultura(array $request)
  {
    return DB::transaction(function () use ($request) {
      $cultura = self::create($request);

      $imageService = app(\App\Services\ImageService::class);
      $imageService->processModelRichText($cultura, $request, self::$richTextFields);

      if (! empty($request['anno_fundacion'])) {
        $cultura->fundacion_id = Fecha::sync(null, [
          'dia' => $request['dia_fundacion'] ?? null,
          'mes' => $request['mes_fundacion'] ?? null,
          'anno' => $request['anno_fundacion'] ?? null,
        ]);
      }

      if (! empty($request['anno_disolucion'])) {
        $cultura->disolucion_id = Fecha::sync(null, [
          'dia' => $request['dia_disolucion'] ?? null,
          'mes' => $request['mes_disolucion'] ?? null,
          'anno' => $request['anno_disolucion'] ?? null,
        ]);
      }

      $cultura->save();

      $cultura->subirImagenesReferencia($request['imagenes_referencia'] ?? []);

      return $cultura;
    });
  }

  public function update_cultura(array $request)
  {
    return DB::transaction(function () use ($request) {
      $this->fill($request);

      $imageService = app(\App\Services\ImageService::class);
      $imageService->processModelRichText($this, $request, self::$richTextFields);

      if (! empty($request['anno_fundacion'])) {
        $this->fundacion_id = Fecha::sync($this->fundacion_id, [
          'dia' => $request['dia_fundacion'] ?? null,
          'mes' => $request['mes_fundacion'] ?? null,
          'anno' => $request['anno_fundacion'] ?? null,
        ]);
      }

      if (! empty($request['anno_disolucion'])) {
        $this->disolucion_id = Fecha::sync($this->disolucion_id, [
          'dia' => $request['dia_disolucion'] ?? null,
          'mes' => $request['mes_disolucion'] ?? null,
          'anno' => $request['anno_disolucion'] ?? null,
        ]);
      }

      $this->subirImagenesReferencia($request['imagenes_referencia'] ?? []);

      return $this->save();
    });
  }

  protected static function booted()
  {
    static::deleting(function ($cultura) {
      app(\App\Services\ImageService::class)->deleteImagesByOwner('culturas', $cultura->id);

      if ($cultura->fundacion_id) {
        \App\Models\Fecha::destroy($cultura->fundacion_id);
      }

      if ($cultura->disolucion_id) {
        \App\Models\Fecha::destroy($cultura->disolucion_id);
      }

      $cultura->culturas_hijas()->update(['madre_id' => null]);
    });
  }
}
