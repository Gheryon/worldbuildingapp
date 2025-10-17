<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class tipo_asentamiento extends Model
{
    use HasFactory;

    protected $table = 'tipo_asentamiento';
    protected $primaryKey = 'id';
    public $timestamps=false;

    protected $fillable = [
        'nombre',
    ];

   /* Obtiene los tipos de asentamientos ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function get_tipos_asentamientos()
  {
    try {
      $tipos = self::select('id', 'nombre')->orderBy('nombre', 'asc')->get();

      if ($tipos->isEmpty()) {
        Log::warning('No se encontraron tipos de organizaciones.');
        $tipos = ['error' => ['error' => 'No hay tipos de organizaciones guardados.']];
      }

      return $tipos;
    } catch (\Exception $e) {
      Log::error('Error al obtener tipos: ' . $e->getMessage());
      $tipos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      return $tipos;
    }
  }
}
