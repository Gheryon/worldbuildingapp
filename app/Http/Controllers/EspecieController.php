<?php

namespace App\Http\Controllers;

use App\Models\Especie;
use App\Models\imagen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EspecieController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $datosValidados = $request->validate([
      'orden' => 'sometimes|string|in:asc,desc', // 'sometimes' permite que no esté presente.
      'search' => 'sometimes|nullable|string|max:100',
    ], [
      'orden.in' => 'El orden debe ser ascendente (asc) o descendente (desc).',
    ]);

    // Si la validación falla o el parámetro no está presente, se usan los valores por defecto.
    $orden = $datosValidados['orden'] ?? 'asc';
    $terminoBusqueda = $datosValidados['search'] ?? null;

    // Obtener todas las especies almacenadas
    //$especies = Especie::get_especies();
    $especies = Especie::filtrar([
      'orden'  => $orden,
      'search' => $terminoBusqueda
    ])->paginate(18);

    return view('especies.index', ['especies' => $especies, 'orden' => $request->orden ?? 'asc']);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('especies.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre' => 'required|max:255',
      'estatus' => 'required',
    ]);

    try {
      $especie = Especie::store_especie($request);

      return redirect()->route('especies.index')
        ->with('success', 'Especie ' . $especie->nombre . ' añadida correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error("Error de base de datos al añadir especie.", [
        'entrada_input' => $request->all(),
        'error' => $e->getMessage()
      ]);
      return redirect()->back()->withInput()->with('error', 'Error en la base de datos al crear la especie.');
    } catch (\Exception $e) {
      Log::critical("Error inesperado al añadir especie.", [
        'entrada_input' => $request->all(),
        'error' => $e->getMessage()
      ]);
      return redirect()->back()->withInput()->with('error', 'No se pudo crear: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      // Cargamos la especie con sus fechas
      $especie = Especie::findOrFail($id);

      return view('especies.show', compact('especie'));
    } catch (\Exception $e) {
      Log::error("Error al mostrar especie: " . $e->getMessage());
      return redirect()->route('especies.index')
        ->with('error', 'Especie no encontrada.');
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    try {
      //obtener especie
      $especie = Especie::findOrFail($id);
      return view('especies.edit', compact('especie'));
    } catch (\Exception $e) {
      // Si hay un error de lógica, redirigimos con un mensaje flash
      return redirect()->route('especies.index')
        ->with('error', 'No se pudo cargar la especie: ' . $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'nombre' => 'required|max:255',
      'estatus' => 'required',
    ]);

    try {
      $especie = Especie::findOrFail($id);
      $especie->update_especie($request);

      return redirect()->route('especies.index')
        ->with('success', 'Especie ' . $especie->nombre . ' actualizada correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error("Error de base de datos al actualizar especie.", [
        'entrada_input' => $request->all(),
        'error' => $e->getMessage()
      ]);
      return redirect()->back()->withInput()->with('error', 'Error en la base de datos al actualizar la especie.');
    } catch (\Exception $e) {
      Log::critical("Error inesperado al actualizar especie.", [
        'entrada_input' => $request->all(),
        'error' => $e->getMessage()
      ]);
      return redirect()->back()->withInput()->with('error', 'No se pudo actualizar: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    $id = $request->id_borrar;

    try {
      $especie = Especie::findOrFail($id);
      $nombre = $especie->nombre;

      $especie->delete_especie();

      return redirect()->route('especies.index')
        ->with('success', "La especie '{$nombre}' y sus recursos han sido eliminados.");
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return redirect()->route('especies.index')
        ->with('error', 'La especie que intenta eliminar no existe.');
    } catch (\Exception $e) {
      Log::error("Error crítico al eliminar especie ID {$id}: " . $e->getMessage());

      return redirect()->route('especies.index')
        ->with('error', 'No se pudo completar la eliminación debido a un error interno.');
    }
  }
}
