<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImagenRequest;
use App\Models\Categoria;
use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImagenController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $query = Imagen::with('categoria');

    if ($request->filled('categoria_id')) {
      $query->where('categoria_id', $request->categoria_id);
    }

    $imagenes = $query->get();
    $categorias = Categoria::orderBy('nombre')->get();
    $categoriaActiva = $request->categoria_id;

    return view('galeria.index', compact('imagenes', 'categorias', 'categoriaActiva'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(ImagenRequest $request)
  {
    try {
      Imagen::subirImagen($request);
      Log::info('Imagen subida correctamente.');

      return redirect()->route('galeria.index')
        ->with('success', 'Imagen subida correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        'Error de base de datos al subir imagen.',
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );

      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo subir la imagen debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        'Error inesperado al subir imagen.',
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );

      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo subir la imagen: Error inesperado');
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(ImagenRequest $request, Imagen $imagen)
  {
    try {
      $imagen->renombrarImagen($request->nombre, $request->categoria_id);
      Log::info('Imagen renombrada correctamente.', ['imagen_id' => $imagen->id]);

      return redirect()->route('galeria.index')->with('success', 'Imagen renombrada correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        'Error de base de datos al renombrar imagen.',
        [
          'entrada_input' => $request->all(),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );

      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo renombrar la imagen debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        'Error inesperado al renombrar imagen.',
        [
          'entrada_input' => $request->all(),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );

      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo renombrar la imagen: Error inesperado');
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Imagen $imagen)
  {
    try {
      $imagen->eliminarImagen();
      Log::info('Imagen eliminada correctamente.', ['imagen_id' => $imagen->id]);

      return redirect()->route('galeria.index')
        ->with('success', 'Imagen eliminada correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        'Error de base de datos al eliminar imagen.',
        [
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );

      return redirect()->back()
        ->with('error', 'No se pudo eliminar la imagen debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        'Error inesperado al eliminar imagen.',
        [
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );

      return redirect()->back()
        ->with('error', 'No se pudo eliminar la imagen: Error inesperado');
    }
  }

  /**
   * Elimina una imagen de referencia asociada a una entidad.
   */
  public function destroyReference($entityType, $entityId, Imagen $imagen)
  {
    if ($imagen->table_owner !== $entityType || (int) $imagen->owner !== (int) $entityId) {
      abort(404);
    }

    try {
      DB::transaction(function () use ($imagen) {
        if (Storage::disk('public')->exists($imagen->path)) {
          Storage::disk('public')->delete($imagen->path);
        }
        $imagen->delete();
      });
      Log::info('Imagen de referencia eliminada correctamente.', ['imagen_id' => $imagen->id, 'entityType' => $entityType, 'entityId' => $entityId]);

      return response()->json(['success' => true, 'message' => 'Imagen eliminada.']);
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error('Error de base de datos al eliminar imagen de referencia.', [
        'imagen_id' => $imagen->id,
        'error' => $e->getMessage(),
      ]);

      return response()->json(['success' => false, 'message' => 'No se pudo eliminar la imagen.'], 500);
    } catch (\Exception $e) {
      Log::critical('Error inesperado al eliminar imagen de referencia.', [
        'imagen_id' => $imagen->id,
        'error' => $e->getMessage(),
      ]);

      return response()->json(['success' => false, 'message' => 'Error inesperado al eliminar la imagen.'], 500);
    }
  }
}
