<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class tipo_lugar extends Model
{
  use HasFactory;
  protected $table = 'tipo_lugar';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
  ];

  /* Obtiene los tipos de lugares ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_tipos_lugares()
  {
    try {
      $tipos = self::orderBy('nombre', 'asc')->get();

      if ($tipos->isEmpty()) {
        Log::warning('tipo_lugar->get_tipos_lugares: No se encontraron tipos de lugares.');
        $tipos = ['error' => ['error' => 'No hay tipos de lugares guardados.']];
      }
    } catch (\Exception $e) {
      Log::error('tipo_lugar->get_tipos_lugares: Error al obtener tipos: ' . $e->getMessage());
      $tipos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }
    return $tipos;
  }
}
