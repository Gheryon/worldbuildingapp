<?php

namespace App\Services;

use App\Models\Imagen;
use Illuminate\Support\Facades\File;

class ImageService
{
  /**
   * Procesa el HTML, guarda imágenes base64 en disco y crea registros en la BD.
   */
  public function processSummernoteImages($content, string $tableOwner, int $idOwner)
  {
    if (empty($content)) return $content;

    //asegurar que existe el directorio
    $storagePath = public_path("storage/imagenes/");
    if (!File::exists($storagePath)) {
      File::makeDirectory($storagePath, 0755, true);
    }

    $dom = new \DomDocument();
    //Mantener UTF-8 y evitar errores de HTML5
    $htmlForDom = '<?xml encoding="utf-8" ?>' . $content;
    @$dom->loadHtml($htmlForDom, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    $images = $dom->getElementsByTagName('img');

    foreach ($images as $item => $image) {
      $src = $image->getAttribute('src');

      if (preg_match('/^data:image\/(\w+);base64,/', $src)) {
        list($type, $data) = explode(';', $src);
        list(, $data) = explode(',', $data);
        $imageData = base64_decode($data);
        //generar nombre único
        $imageName = time() . "_obj_" . $idOwner . "_" . $item . '.png';
        $path = public_path("storage/imagenes/" . $imageName);

        // Guardar archivo físico
        file_put_contents($path, $imageData);

        // Actualizar el HTML para que apunte a la URL pública
        $newUrl = asset("storage/imagenes/" . $imageName);
        $image->setAttribute('src', $newUrl);

        // Crear registro en tabla 'imagenes'
        Imagen::create([
          'owner' => $idOwner,
          'table_owner' => $tableOwner,
          'nombre' => $imageName,
          'path' => $newUrl,
        ]);
      }
    }
    //Retornar solo el contenido interno, eliminando el prefijo XML del principio
    $resultHtml = $dom->saveHTML();
    return str_replace('<?xml encoding="utf-8" ?>', '', $resultHtml);
  }

  /**
   * Elimina archivos físicos y registros de la BD asociados a un dueño específico.
   * 
   * @param string $tableOwner Nombre de la tabla propietaria.
   * @param int $idOwner ID del dueño.
   */
  public function deleteImagesByOwner(string $tableOwner, int $idOwner)
  {
    // Obtenemos los registros de la tabla imagenes
    $imagenes = \App\Models\Imagen::where('table_owner', $tableOwner)
      ->where('owner', $idOwner)
      ->get();

    foreach ($imagenes as $imagen) {
      // Ruta absoluta al archivo físico
      $filePath = public_path("storage/imagenes/" . $imagen->nombre);

      if (file_exists($filePath)) {
        unlink($filePath); // Borra el archivo del servidor
      }

      $imagen->delete(); // Borra el registro de la BD
    }
  }
}
