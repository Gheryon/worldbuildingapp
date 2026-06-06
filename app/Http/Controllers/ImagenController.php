<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use App\Models\articulo;
use App\Models\Construccion;
use App\Models\Personaje;
use App\Models\Lugar;
use App\Models\Organizacion;
use App\Models\Especie;
use App\Models\Religion;
use App\Models\Conflicto;
use App\Models\Asentamiento;
use App\Models\Evento;
use App\Http\Requests\ImagenRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImagenController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $imagenes = Imagen::all();

    //cambia el nombre de la imagen por el del dueño de la imagen a las que provienen de summernote
    foreach ($imagenes as $imagen) {
      $imagen->owner_name = $imagen->table_owner === 'galeria'
        ? null
        : $this->getOwnerName($imagen->table_owner, $imagen->owner);
    }

    return view('galeria.index', compact('imagenes'));
  }

  /**
   * Obtiene el nombre del dueño de una imagen de summernote.
   */
  private function getOwnerName(string $tableOwner, int $ownerId): string
  {
    $model = match ($tableOwner) {
      'articulos'       => new articulo(),
      'construcciones'  => new Construccion(),
      'personajes'      => new Personaje(),
      'lugares'         => new Lugar(),
      'organizaciones'  => new Organizacion(),
      'especies'        => new Especie(),
      'religiones'      => new Religion(),
      'conflictos'      => new Conflicto(),
      'asentamientos'   => new Asentamiento(),
      'eventos'         => new Evento(),
      default           => null,
    };

    if (!$model) return 'Desconocido';

    $record = $model->newQuery()->find($ownerId);
    return $record ? $record->nombre : 'Desconocido';
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(ImagenRequest $request)
  {
    $imageFile = $request->file('imagen');
    $cleanName = preg_replace('/\s+$/', '', $request->nombre);
    $filename = $cleanName . '_' . time() . '.' . $imageFile->getClientOriginalExtension();

    $path = $imageFile->storeAs('imagenes', $filename, 'public');

    Imagen::create([
      'nombre' => basename($path),
      'path' => $path,
      'owner' => 0,
      'table_owner' => 'galeria',
    ]);

    return redirect()->route('galeria.index');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(ImagenRequest $request, $id)
  {
    $imagen = Imagen::findOrFail($id);

    if ($imagen->table_owner !== 'galeria') {
      return redirect()->route('galeria.index')->with('error', 'No se puede renombrar esta imagen.');
    }

    $oldPath = 'imagenes/' . $imagen->nombre;
    $cleanName = preg_replace('/\s+$/', '', $request->nombre);
    $extension = pathinfo($imagen->nombre, PATHINFO_EXTENSION);
    $newFilename = $cleanName . '_' . time() . '.' . $extension;
    $newPath = 'imagenes/' . $newFilename;

    if (Storage::disk('public')->exists($oldPath)) {
      Storage::disk('public')->move($oldPath, $newPath);
    }

    $imagen->update([
      'nombre' => $newFilename,
      'path' => $newPath,
    ]);

    return redirect()->route('galeria.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $imagen = Imagen::findOrFail($id);

    if ($imagen->table_owner !== 'galeria') {
      return redirect()->route('galeria.index')->with('error', 'No se puede eliminar esta imagen.');
    }

    $path = 'imagenes/' . $imagen->nombre;

    if (Storage::disk('public')->exists($path)) {
      Storage::disk('public')->delete($path);
    }

    $imagen->delete();

    return redirect()->route('galeria.index');
  }

  /**
   * Elimina obtiene los id de los artículos de la tabla imágenes, analiza aquellos presentes y borra imágenes que no se usan en ninguno.
   */
  public function limpiar_imagenes(string $table_owner = "a", int $id_owner = 0)
  {
    //id_articulos contiene el id de los articulos que contienen alguna imagen
    $id_articulos = DB::table('imagenes')->select('owner')->where('table_owner', '=', 'articulos')->distinct()->get();
    //var_dump($id_articulos);
    $imagenes = DB::table('imagenes')
      ->select('id', 'path')
      ->where('table_owner', '=', 'articulos')->get();

    foreach ($id_articulos as $id_articulo) {
      //var_dump($id_articulo->owner);
      $articulo = DB::table('articulosgenericos')
        ->select('contenido')
        ->where('id_articulo', '=', $id_articulo->owner)->get();
      //var_dump($articulo);

      $dom = new \DomDocument();
      libxml_use_internal_errors(true);
      $dom->loadHtml($articulo, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | libxml_use_internal_errors(true));
      $imageFile = $dom->getElementsByTagName('img');

      foreach ($imageFile as $item => $image) {
        $data = $image->getAttribute('src');
        //echo $data;
        var_dump($imagenes);
        if ($imagenes->contains('path', $data)) {
          echo "encontrado";
        }
      }
    }
    foreach ($imagenes as $imagen) {
      //if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
      //unlink(public_path("/storage/imagenes/" . $imagen->nombre));
      //Storage::delete(asset($imagen->nombre));
      //}
      //imagen::destroy($imagen->id);
    }
  }
}
