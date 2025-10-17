<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class tipo_construccion extends Model
{
  use HasFactory;

  protected $table = 'tipo_construccion';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
  ];

  /* Obtiene los tipos de construcciones ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_tipos_construcciones()
  {
    try {
      $tipos = self::orderBy('nombre', 'asc')->get();

      if ($tipos->isEmpty()) {
        Log::warning('tipo_construccion->get_tipos_construcciones: No se encontraron tipos de construcciones.');
        $tipos = ['error' => ['error' => 'No hay tipos de construcciones guardadas.']];
      }
    } catch (\Exception $e) {
      Log::error('tipo_construccion->get_tipos_construcciones: Error al obtener tipos: ' . $e->getMessage());
      $tipos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $tipos;
  }
}
