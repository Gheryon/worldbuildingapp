<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Nombres extends Model
{
  use HasFactory;

  protected $table = 'nombres';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
    'lista',
    'tipo',
  ];

  /**
   * Obtiene el nombre del mundo.
   *
   * @return string|null El valor del campo 'lista' si se encuentra, null en caso contrario o si hay un error.
   */
  public static function get_nombre_mundo(): ?string
  {
    try {
      // El método value() de Eloquent, extrae directamente el valor de la columna de la base de datos.
      // Es más rápido y consume menos memoria, ya que solo devuelve el dato buscado.
      return self::where('tipo', 'Nombre_mundo')->value('lista');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al obtener el nombre del mundo.",
        [
          'tipo_buscado' => 'Nombre_mundo',
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      // Devuelve null si no se encuentra.
      return null;
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al obtener el nombre del mundo.",
        [
          'tipo_buscado' => 'Nombre_mundo',
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return null;
    }
  }

  /**
   * Actualiza o inserta el nombre del mundo en la base de datos.
   *
   * @param string $nuevoNombre El nuevo nombre del mundo a guardar.
   * @return bool True si la operación fue exitosa, false en caso de error o si el nombre está vacío.
   */
  public static function update_nombre_mundo(string $nuevoNombre): bool
  {
    // 1. Validación de entrada: Un nombre de mundo no debería estar vacío.
    if (empty(trim($nuevoNombre))) {
      Log::warning("Intento de actualizar el nombre del mundo con un valor vacío.");
      return false;
    }

    try {
      // Uso de transacción para que la operación sea atómica. Si algo falla, todo se revierte.
      DB::transaction(function () use ($nuevoNombre) {
        // Lógica de "Upsert": Actualizar o Insertar. UpdateOrCreate es el método de Eloquent para esto.
        self::updateOrCreate(
          ['tipo' => 'Nombre_mundo'], // Condiciones de búsqueda
          ['lista' => trim($nuevoNombre)] // Valores a guardar/actualizar
        );
      });

      return true;
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al actualizar el nombre del mundo.",
        [
          'nuevo_nombre' => $nuevoNombre,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return false;
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al actualizar el nombre del mundo.",
        [
          'nuevo_nombre' => $nuevoNombre,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return false;
    }
  }

  /**
   * Obtiene los nombres de los hombres del mundo.
   *
   * @return string|null El valor del campo 'lista' si se encuentra, null en caso contrario o si hay un error.
   */
  public static function get_nombres_hombres(): ?string
  {
    try {
      return self::where('tipo', 'Hombres')->value('lista');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al obtener los nombres de los hombres.",
        [
          'tipo_buscado' => 'Hombres',
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      // Devuelve null si no se encuentra.
      return null;
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al obtener los nombres de los hombres.",
        [
          'tipo_buscado' => 'Hombres',
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return null;
    }
  }

  /**
   * Obtiene los nombres de las mujeres del mundo.
   *
   * @return string|null El valor del campo 'lista' si se encuentra, null en caso contrario o si hay un error.
   */
  public static function get_nombres_mujeres(): ?string
  {
    try {
      return self::where('tipo', 'Mujeres')->value('lista');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al obtener los nombres de las mujeres.",
        [
          'tipo_buscado' => 'Mujeres',
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      // Devuelve null si no se encuentra.
      return null;
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al obtener los nombres de las mujeres.",
        [
          'tipo_buscado' => 'Mujeres',
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return null;
    }
  }

  /**
   * Obtiene los nombres de lugares del mundo.
   *
   * @return string|null El valor del campo 'lista' si se encuentra, null en caso contrario o si hay un error.
   */
  public static function get_nombres_lugares(): ?string
  {
    try {
      return self::where('tipo', 'Lugares')->value('lista');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al obtener los nombres de los lugares.",
        [
          'tipo_buscado' => 'Lugares',
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      // Devuelve null si no se encuentra.
      return null;
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al obtener los nombres de los lugares.",
        [
          'tipo_buscado' => 'Lugares',
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return null;
    }
  }

  /**
   * Obtiene los nombres indeterminados del mundo.
   *
   * @return string|null El valor del campo 'lista' si se encuentra, null en caso contrario o si hay un error.
   */
  public static function get_nombres_indeterminados(): ?string
  {
    try {
      return self::where('tipo', 'Sin_decidir')->value('lista');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al obtener los nombres indeterminados.",
        [
          'tipo_buscado' => 'Sin_decidir',
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      // Devuelve null si no se encuentra.
      return null;
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al obtener los nombres indeterminados.",
        [
          'tipo_buscado' => 'Sin_decidir',
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return null;
    }
  }

  /**
   * Añade un nuevo nombre a la lista de nombres y los ordena alfabéticamente y sin duplicados.
   *
   * @param string $nombre_nuevo El nombre a añadir a la lista.
   * @param string $tipo El tipo de lista (ej: 'personajes_masculinos', 'ciudades', etc.).
   * @return bool True si la operación fue exitosa (o si el nombre ya existía), false en caso de error.
   */
  public static function store_nombre(string $nombre_nuevo, string $tipo): bool
  {
    if (empty($nombre_nuevo) || empty($tipo)) {
      Log::warning("Intento de añadir un nombre con datos inválidos.", [
        'nombre_nuevo' => $nombre_nuevo,
        'tipo' => $tipo,
      ]);
      return false;
    }
    try {
      $resultado = DB::transaction(function () use ($nombre_nuevo, $tipo) {
        $registro = self::firstOrCreate(
          ['tipo' => $tipo],
          ['lista' => ''] // Crear con una lista vacía si no existe
        );

        //Obtener la lista actual, convertirla a un array y filtrar elementos vacíos.
        $nombresArray = $registro->lista ? explode(', ', $registro->lista) : [];
        $nombresArray = array_filter($nombresArray, 'trim');

        //Comprobar si el nombre ya existe para evitar duplicados.
        if (in_array($nombre_nuevo, $nombresArray)) {
          Log::info(
            "El nombre ya existe en la lista. No se realizaron cambios.",
            [
              'nombre' => $nombre_nuevo,
              'tipo' => $tipo,
            ]
          );
          return true;
        }

        //Añadir el nuevo nombre al array, ordenar alfabéticamente y convertir a cadena separada por comas.
        $nombresArray[] = $nombre_nuevo;
        sort($nombresArray);
        $nuevaListaString = implode(', ', $nombresArray);

        $registro->lista = $nuevaListaString;
        $registro->save();

        return true;
      });

      return $resultado;
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al añadir el nombre '{$nombre_nuevo}' al tipo '{$tipo}'.",
        [
          'nombre_nuevo' => $nombre_nuevo,
          'tipo' => $tipo,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return false;
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir el nombre '{$nombre_nuevo}' al tipo '{$tipo}'.",
        [
          'nombre_nuevo' => $nombre_nuevo,
          'tipo' => $tipo,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return false;
    }
  }

  /**
   * Actualiza la lista de nombres y los ordena alfabéticamente y sin duplicados.
   *
   * @param string $nombres La lista de nombres a actualizar.
   * @param string $tipo El tipo de lista (ej: 'personajes_masculinos', 'ciudades', etc.).
   * @return bool True si la operación fue exitosa (o si el nombre ya existía), false en caso de error.
   */
  public static function update_nombres(string $nombres, string $tipo): bool
  {
    if (empty($nombres) || empty($tipo)) {
      Log::warning("Intento de añadir un nombre con datos inválidos.", [
        'nombres' => $nombres,
        'tipo' => $tipo,
      ]);
      return false;
    }
    try {
      $resultado = DB::transaction(function () use ($nombres, $tipo) {
        $registro = self::firstOrCreate(
          ['tipo' => $tipo],
          ['lista' => ''] // Crear con una lista vacía si no existe
        );

        //Reemplazar saltos de línea y tabulaciones por comas para unificar separadores.
        $cadenaLimpia = preg_replace('/[\r\n\t]+/', ', ', $nombres);

        //Convertir la cadena a un array y filtrar elementos vacíos.
        $nombresArray = explode(', ', $cadenaLimpia);

        //Filtrar el array:
        // - 'trim' elimina espacios alrededor de cada nombre.
        // - 'strlen' verifica que el nombre no esté vacío después del trim.
        $nombresArray = array_filter($nombresArray, function ($nombre) {
          return strlen(trim($nombre)) > 0;
        });

        //Eliminar duplicados, reindexar el array (importante para el sort) y ordenar alfabéticamente .
        $nombresArray = array_unique($nombresArray);
        $nombresArray = array_values($nombresArray);
        sort($nombresArray);

        //Convertir el array de nuevo a una cadena separada por comas
        $nuevaListaString = implode(', ', $nombresArray);

        $registro->lista = $nuevaListaString;
        $registro->save();

        return true;
      });

      return $resultado;
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al actualizar la lista de nombres con tipo: {$tipo}.",
        [
          'tipo' => $tipo,
          'entrada_input' => $nombres,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return false;
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al actualizar la lista de nombres con tipo: {$tipo}.",
        [
          'tipo' => $tipo,
          'entrada_input' => $nombres,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return false;
    }
  }
}
