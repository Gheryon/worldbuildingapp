<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Http\Requests\ImagenRequest;
use Illuminate\Support\Facades\Log;

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
      return redirect()->route('galeria.index')
        ->with('success', 'Imagen subida correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al subir imagen.",
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
        "Error inesperado al subir imagen.",
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
      return redirect()->route('galeria.index')->with('success', 'Imagen renombrada correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al renombrar imagen.",
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
        "Error inesperado al renombrar imagen.",
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
      return redirect()->route('galeria.index')
        ->with('success', 'Imagen eliminada correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al eliminar imagen.",
        [
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->with('error', 'No se pudo eliminar la imagen debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al eliminar imagen.",
        [
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->with('error', 'No se pudo eliminar la imagen: Error inesperado');
    }
  }
}
