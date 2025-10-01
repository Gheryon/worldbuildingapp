<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class personaje extends Model
{
  use HasFactory;

  protected $table = 'personaje';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'Nombre',
    'nombreFamilia',
    'Apellidos',
    'lugar_nacimiento',
    'nacimiento',
    'fallecimiento',
    'causa_fallecimiento',
    'Descripcion',
    'DescripcionShort',
    'Personalidad',
    'salud',
    'Deseos',
    'Miedos',
    'Magia',
    'educacion',
    'Historia',
    'Religion',
    'Familia',
    'Politica',
    'Retrato',
    'id_foranea_especie',
    'sexo',
    'otros'
  ];

  /**
   * Obtiene los personajes con id diferente de 0, ordenados por nombre.
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public static function getPersonajes()
  {
    try {
      $personajes = self::where('id', '!=', 0)
        ->select('id', 'Nombre')
        ->orderBy('Nombre', 'asc')
        ->get();

      if ($personajes->isEmpty()) {
        Log::warning('No se encontraron personajes con id diferente de 0.');
        $personajes = ['error' => ['error' => 'No hay personajes guardados.']];
      }

      return $personajes;
    } catch (\Exception $e) {
      Log::error('Error al obtener personajes: ' . $e->getMessage());
      $personajes = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      return $personajes;
    }
  }
}
