<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Fecha extends Model
{
  use HasFactory;

  protected $table = 'fechas';
  public $timestamps = true;

  protected $fillable = [
    'dia',
    'mes',
    'anno',
  ];

  /**
   * Obtiene una fecha en forma de cadena (dia/mes/anno) por su ID. Usada para mostrar fechas en vistas
   *
   * @param int|null $id El ID de la fecha.
   * @return string La fecha formateada o "Desconocida" si no se encuentra.
   */
  public static function get_fecha_string(?int $id): string
  {
    // Si el ID es nulo o 0 (valor por defecto en tu lógica), evitamos la consulta
    if (!$id || $id <= 1) {
      return "Desconocida";
    }

    try {
      $fecha = self::find($id);

      if (!$fecha) {
        return "Desconocida";
      }

      //si dia y mes son 0, devolvemos solo el año
      if ($fecha->dia == 0 && $fecha->mes == 0) {
        return $fecha->anno;
      }

      return "{$fecha->dia}/{$fecha->mes}/{$fecha->anno}";
    } catch (\Exception $e) {
      Log::error("Error al formatear fecha ID {$id}: " . $e->getMessage());
      return "Error de formato";
    }
  }

  /**
   * Crea un nuevo registro de fecha y devuelve su ID. Si no se almacena ninguna fecha, devuelve 2.
   *
   * @param int $dia, $mes, $anno
   * @return int ID de la fecha creada o 0 si los datos son vacíos.
   */
  public static function store_fecha($dia = 0, $mes = 0, $anno = 0)
  {
    // Si no se introduce ningún dato, la fecha es indeterminada, se devuelve 2.
    if ($anno == 0 && $mes == 0 && $dia == 0) {
      return null;
    }

    $fecha = self::create([
      'dia'   => $dia,
      'mes'   => $mes,
      'anno'  => $anno,
    ]);

    return $fecha->id;
  }

  /**
   * Actualiza una fecha específica en la base de datos según su ID.
   *
   * @param int $id El ID de la fecha que se desea actualizar.
   * @param int $dia El día de la fecha (puede ser nulo).
   * @param int $mes El mes de la fecha (puede ser nulo).
   * @param int|null $anno El año de la fecha (puede ser nulo).
   * @return bool True si la operación fue exitosa, false en caso de error.
   */
  public static function update_fecha(?int $dia, ?int $mes, ?int $anno, int $id): bool
  {
    if ($id <= 1) {
      Log::warning("Intento de actualizar fechas reservadas o inválidas.", [
        'id' => $id,
      ]);
      return false;
    }

    if ($dia < 0 || $mes < 0) {
      Log::warning("Intento de actualizar una fecha con valores inválidos para día o mes.", [
        'dia' => $dia,
        'mes' => $mes,
        'anno' => $anno,
      ]);
      return false;
    }

    try {
      DB::transaction(function () use ($id, $dia, $mes, $anno) {
        $fecha = self::find($id);
        // Si no se encuentra el registro, no se puede actualizar.
        if (!$fecha) {
          Log::warning("No se encontró la fecha para actualizar.", [
            'id_buscado' => $id,
          ]);
          return false;
        }

        $fecha->dia = $dia;
        $fecha->mes = $mes;
        $fecha->anno = $anno;
        $fecha->save();
      });

      return true;
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al actualizar fecha con id={$id}.",
        [
          'datos_enviados' => compact('id', 'dia', 'mes', 'anno'),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return false;
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al actualizar fecha con id={$id}.",
        [
          'datos_enviados' => compact('id', 'dia', 'mes', 'anno'),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return false;
    }
  }

  /**
   * Obtiene la fecha del mundo, la cual se guarda con id=1. Si no se encuentra, devuelve null.
   *
   * @return \App\Models\Fecha|null El modelo de la Fecha si se encuentra, null en caso contrario o si hay un error.
   */
  public static function get_fecha_mundo(): ?Fecha
  {
    try {
      // Devuelve el modelo encontrado o null si no existe.
      return self::find(1);
    } catch (\Illuminate\Database\QueryException $e) {
      // Captura de errores de la base de datos.
      Log::error(
        "Error de base de datos al buscar la fecha del mundo.",
        [
          'id' => 1,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return null;
    } catch (\Exception $e) {
      // Captura de otra excepción inesperada.
      Log::critical(
        "Error inesperado al buscar la fecha del mundo.",
        [
          'id' => 1,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return null;
    }
  }

  /**
   * Actualiza o inserta la fecha del mundo, la cual siempre debe
   * tener el ID reservado igual a 1.
   *
   * Esta función garantiza que el registro con ID=1 exista. Si no existe, lo crea.
   * Si existe, lo actualiza con los nuevos valores de día, mes y año.
   *
   * @param int $dia El día de la fecha.
   * @param int $mes El mes de la fecha.
   * @param int|null $anno El año de la fecha (puede ser nulo).
   * @return bool True si la operación fue exitosa, false en caso de error.
   */
  public static function update_fecha_mundo(int $dia, int $mes, ?int $anno): bool
  {
    if ($dia < 0 || $mes < 0) {
      Log::warning("Intento de actualizar la fecha del mundo con valores inválidos para día o mes.", [
        'dia' => $dia,
        'mes' => $mes,
        'anno' => $anno,
      ]);
      return false;
    }

    try {
      DB::transaction(function () use ($dia, $mes, $anno) {
        // Usamos updateOrCreate para buscar por ID (clave primaria) y actualizar o crear.
        self::updateOrCreate(
          ['id' => 1], // Condición: buscar el registro con ID = 1
          [
            'dia' => $dia,
            'mes' => $mes,
            'anno' => $anno,
          ]
        );
      });

      return true;
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al actualizar la fecha del mundo (ID=1).",
        [
          'datos_enviados' => compact('dia', 'mes', 'anno'),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return false;
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al actualizar la fecha del mundo (ID=1).",
        [
          'datos_enviados' => compact('dia', 'mes', 'anno'),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return false;
    }
  }
}
