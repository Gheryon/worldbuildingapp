<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class tipo_conflicto extends Model
{
  use HasFactory;

  protected $table = 'tipo_conflicto';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
  ];

  /* Obtiene los tipos de conflictos ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_tipos_conflictos()
  {
    try {
      $tipos = self::orderBy('nombre', 'asc')->get();

      if ($tipos->isEmpty()) {
        Log::warning('No se encontraron tipos de conflictos.');
        $tipos = ['error' => ['error' => 'No hay tipos de conflictos guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('Error al obtener tipos: ' . $e->getMessage());
      $tipos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $tipos;
  }
}
