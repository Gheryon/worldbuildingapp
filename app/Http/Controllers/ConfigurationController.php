<?php

namespace App\Http\Controllers;

use App\Models\Nombres;
use App\Models\Fecha;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class ConfigurationController extends Controller
{
  //Mapa de modelos
  private $modelMap = [
    'asentamiento'   => \App\Models\TipoAsentamiento::class,
    'conflicto'      => \App\Models\TipoConflicto::class,
    'construccion'   => \App\Models\TipoConstruccion::class,
    'lugar'          => \App\Models\TipoLugar::class,
    'organizacion'   => \App\Models\TipoOrganizacion::class,
    'categorias'     => \App\Models\Categoria::class,
  ];

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $data = [
      'Nombre_mundo' => Nombres::get_nombre_mundo(), //
      'fecha'        => Fecha::get_fecha_mundo(),  //
    ];

    // Mapeo para automatizar la carga
    $catalogos = [
      'tipos_asentamiento'    => \App\Models\TipoAsentamiento::class,
      'tipos_conflicto'       => \App\Models\TipoConflicto::class,
      'tipos_construccion'    => \App\Models\TipoConstruccion::class,
      'tipos_lugar'           => \App\Models\TipoLugar::class,
      'tipos_organizaciones'  => \App\Models\TipoOrganizacion::class,
      'categorias'            => \App\Models\Categoria::class,
    ];

    foreach ($catalogos as $key => $modelClass) {
      try {
        // Asumimos que todos tienen el método ordenado o usamos Eloquent directo
        $data[$key] = $modelClass::orderBy('nombre', 'asc')->get();
      } catch (Exception $e) {
        Log::error("Error cargando $key: " . $e->getMessage());
        $data[$key] = collect(); // Devolvemos colección vacía para no romper el @foreach en la vista
      }
    }

    return view('config.index', $data);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage (Generic version).
   */
  public function store(Request $request, $type)
  {
    //Verificar si el tipo existe en el mapa
    if (!isset($this->modelMap[$type])) {
      return redirect()->route('config.index')->with('error', 'Tipo de configuración no válido.');
    }

    //Validar dinámicamente según el nombre del input que envía el componente
    $inputName = "nuevo_tipo_{$type}";
    $request->validate([
      $inputName => 'required|string|max:128',
    ]);

    try {
      $modelClass = $this->modelMap[$type];

      //Crear el registro usando Mass Assignment (requiere $fillable en el modelo)
      $nuevo = $modelClass::create([
        'nombre' => $request->input($inputName)
      ]);

      return redirect()->route('config.index')
        ->with('success', "{$nuevo->nombre} añadido correctamente a {$type}.");
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al guardar.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->route('config.index')->with('error', 'Error de base de datos al guardar.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al guardar.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->route('config.index')->with('error', 'Error inesperado al guardar.');
    }
  }

  /**
   * Update the specified resource in storage (Generic version).
   */
  public function update(Request $request)
  {
    $request->validate([
      'id_editar'     => 'required|integer',
      'tipo_editar'   => 'required|string',
      'nombre_editar' => 'required|string|max:128',
    ]);

    // Verificar si el tipo existe en el mapa de modelos
    if (!isset($this->modelMap[$request->tipo_editar])) {
      return redirect()->route('config.index')->with('error', 'Tipo de configuración no válido.');
    }

    try {
      $modelClass = $this->modelMap[$request->tipo_editar];

      // Buscar el registro
      $registro = $modelClass::findOrFail($request->id_editar);

      //Actualizar y guardar
      $registro->nombre = $request->nombre_editar;
      $registro->save();

      return redirect()->route('config.index')
        ->with('success', "{$registro->nombre} actualizado correctamente.");
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      Log::error(
        "Error al actualizar, el registro no existe.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->route('config.index')->with('error', 'El registro no existe.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al actualizar.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->route('config.index')->with('error', 'Error de base de datos al actualizar.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al actualizar.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->route('config.index')->with('error', 'Error inesperado al actualizar.');
    }
  }

  /**
   * Remove the specified resource from storage (Generic version).
   */
  public function destroy(Request $request)
  {
    $request->validate([
      'id_borrar' => 'required|integer',
      'tipo'      => 'required|string',
    ]);

    //Comprobar si el tipo es válido
    if (!isset($this->modelMap[$request->tipo])) {
      return redirect()->route('config.index')->with('error', 'Tipo de entidad no válido.');
    }

    try {
      $modelClass = $this->modelMap[$request->tipo];

      $registro = $modelClass::findOrFail($request->id_borrar);
      $nombreGuardado = $registro->nombre; // Para el mensaje de confirmación
      $registro->delete();

      return redirect()->route('config.index')
        ->with('success', "El registro '{$nombreGuardado}' ha sido eliminado correctamente.");
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      Log::error(
        "Error al borrar, el registro no existe.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->route('config.index')->with('error', 'El registro no existe.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al borrar.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->route('config.index')->with('error', 'Error de base de datos al borrar.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al eliminar.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->route('config.index')->with('error', 'Error inesperado al eliminar.');
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_nombre_mundo(Request $request)
  {
    $request->validate([
      'nuevo_nombre_mundo' => 'required|string|max:255'
    ]);

    try {
      $nuevoNombre = $request->input('nuevo_nombre_mundo');

      $exito = Nombres::update_nombre_mundo($nuevoNombre);

      return redirect()->route('config.index')
        ->with('success', 'Nombre del mundo actualizado con éxito.');
    } catch (\Exception $e) {
      Log::error("Error al actualizar nombre mundo: " . $e->getMessage());
      return redirect()->route('config.index')->with('error', 'No se pudo actualizar el nombre.');
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_fecha_mundo(Request $request)
  {
    $request->validate([
      'dia'  => 'required|integer|min:1|max:30',
      'mes'  => 'required|integer|min:0|max:12',
      'anno' => 'required|integer',
    ]);

    $exito = Fecha::update_fecha_mundo($request->input('dia', 0), $request->input('mes', 0), $request->input('anno', 0));
    if ($exito) {
      return redirect()->route('config.index')->with('success', 'Fecha del mundo actualizada correctamente.');
    } else {
      return redirect()->route('config.index')->with('error', 'No se pudo actualizar la fecha del mundo.');
    }
  }

  /**
   * Generate and download a backup of the application.
   */
  public function backup()
  {
    try {
      // Limpiar temporalmente LD_LIBRARY_PATH para evitar conflictos con las librerías de XAMPP
      // XAMPP puede generar en ocasiones conflictos con librerías de MySQL como mysqldump, especialmente al usar Spatie Backup.
      if (getenv('LD_LIBRARY_PATH') !== false) {
        putenv('LD_LIBRARY_PATH=');
      }

      // Ejecutar el comando de Spatie para generar el backup
      Artisan::call('backup:run', [
        '--disable-notifications' => true
      ]);

      // Obtener el disco configurado como destino en config/backup.php
      // Por defecto, Spatie suele usar el disco 'local' (storage/app)
      $diskName = config('backup.destination.disks')[0] ?? 'local';
      $disk = Storage::disk($diskName);

      // Buscar el archivo .zip más reciente generado por el paquete
      // Spatie organiza los backups en una carpeta con el nombre de la app o 'Laravel'
      $files = $disk->allFiles();
      $zipFiles = array_filter($files, function ($file) {
        return pathinfo($file, PATHINFO_EXTENSION) === 'zip';
      });

      if (empty($zipFiles)) {
        Log::error("No se pudo encontrar el archivo de copia de seguridad generado.");
        return redirect()->route('config.index')
          ->with('error', 'No se pudo encontrar el archivo de copia de seguridad generado.');
      }

      // Ordenar por fecha de modificación para obtener el más reciente
      usort($zipFiles, function ($a, $b) use ($disk) {
        return $disk->lastModified($b) - $disk->lastModified($a);
      });

      $latestBackup = reset($zipFiles);
      $absolutePath = $disk->path($latestBackup);

      // Retornar la descarga y opcionalmente limpiar el archivo del servidor tras enviarlo
      return response()->download($absolutePath)->deleteFileAfterSend(true);
    } catch (\Exception $e) {
      Log::error("Error al generar la copia de seguridad: " . $e->getMessage());
      return redirect()->route('config.index')
        ->with('error', 'Ocurrió un error al generar la copia de seguridad: ' . $e->getMessage());
    }
  }

  /**
   * Restore a backup from an uploaded file.
   */
  public function restore(Request $request)
  {
    // Aumentar límites de tiempo y memoria para archivos grandes
    set_time_limit(300);
    ini_set('memory_limit', '512M');

    $request->validate([
      'backup_file' => 'required|file|mimes:zip|max:512000', // Máx 500MB
    ]);

    $uploadedFile = $request->file('backup_file');
    $tempZipPath = $uploadedFile->store('restore-temp');
    $absoluteZipPath = storage_path('app/' . $tempZipPath);
    $extractPath = storage_path('app/restore-extracted-' . uniqid());

    $zip = new ZipArchive();
    if ($zip->open($absoluteZipPath) === TRUE) {
      @mkdir($extractPath, 0755, true);
      $zip->extractTo($extractPath);
      $zip->close();
    } else {
      Log::error("Error, no se pudo abrir el archivo ZIP proporcionado: " . $uploadedFile->getClientOriginalName());
      return redirect()->route('config.index')
        ->with('error', 'No se pudo abrir el archivo ZIP proporcionado.');
    }

    try {
      // No se implementa transacción, el archivo .sql actúa como una en sí mismo.
      // Restaurar Base de Datos (.sql)
      $sqlFiles = glob($extractPath . '/**/*.sql');
      if (empty($sqlFiles)) {
        $sqlFiles = glob($extractPath . '/*.sql');
      }

      if (!empty($sqlFiles)) {
        $sqlFile = $sqlFiles[0];
        //Al establecer FOREIGN_KEY_CHECKS=0, se desactivan temporalmente las comprobaciones de claves foráneas
        //lo que permite insertar datos en cualquier orden sin violar restricciones de integridad referencial.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $sqlContent = file_get_contents($sqlFile);
        DB::unprepared($sqlContent);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
      }

      // Restaurar Imágenes (escudos, retratos, imagenes)
      $foldersToRestore = ['escudos', 'retratos', 'imagenes'];
      foreach ($foldersToRestore as $folder) {
        $sourceDir = $extractPath . '/' . $folder;

        // Si no está en la raíz, buscar recursivamente dentro del ZIP extraído
        if (!is_dir($sourceDir)) {
          $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($extractPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
          );
          foreach ($iterator as $item) {
            if ($item->isDir() && $item->getFilename() === $folder) {
              $sourceDir = $item->getPathname();
              break;
            }
          }
        }

        if (is_dir($sourceDir)) {
          $destinationDir = storage_path('app/public/' . $folder);
          if (!file_exists($destinationDir)) {
            mkdir($destinationDir, 0755, true);
          }
          $this->copyDirectory($sourceDir, $destinationDir);
        }
      }

      // Limpieza de archivos temporales
      Storage::delete($tempZipPath);
      $this->deleteDirectory($extractPath);

      return redirect()->route('config.index')
        ->with('success', 'La copia de seguridad se ha restaurado correctamente.');
    } catch (\Exception $e) {
      // Limpiar en caso de error
      Storage::delete($tempZipPath);
      if (file_exists($extractPath)) {
        $this->deleteDirectory($extractPath);
      }

      Log::error("Error al restaurar la copia de seguridad: " . $e->getMessage());
      return redirect()->route('config.index')
        ->with('error', 'Ocurrió un error al restaurar la copia de seguridad: ' . $e->getMessage());
    }
  }

  /**
   * Copia un directorio recursivamente.
   */
  private function copyDirectory($source, $destination)
  {
    $dir = opendir($source);
    @mkdir($destination, 0755, true);
    while (($file = readdir($dir)) !== false) {
      if (($file != '.') && ($file != '..')) {
        if (is_dir($source . '/' . $file)) {
          $this->copyDirectory($source . '/' . $file, $destination . '/' . $file);
        } else {
          copy($source . '/' . $file, $destination . '/' . $file);
        }
      }
    }
    closedir($dir);
  }

  /**
   * Elimina un directorio y su contenido recursivamente.
   */
  private function deleteDirectory($dir)
  {
    if (!file_exists($dir)) return;
    $files = array_diff(scandir($dir), ['.', '..']);
    foreach ($files as $file) {
      $path = $dir . '/' . $file;
      is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
    }
    rmdir($dir);
  }
}
