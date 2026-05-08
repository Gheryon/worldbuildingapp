<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReligionRequest;
use App\Models\Religion;
use App\Models\Fecha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReligionesController extends Controller
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

    // Obtener todas las religiones almacenadas
    //$religiones = Religion::get_religiones();
    $religiones = Religion::filtrar([
      'orden'  => $orden,
      'search' => $terminoBusqueda
    ])->paginate(18);

    return view('religiones.index', ['religiones' => $religiones, 'orden' => $request->orden ?? 'asc']);
  }

  /**
   * Mostrar formulario para crear una nueva religión.
   */
  public function create()
  {
    return view('religiones.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(ReligionRequest $request)
  {
    $datosValidados = $request->validated();

    try {
      $religion = Religion::store_religion($datosValidados);

      return redirect()->route('religiones.index')
        ->with('success', 'Religión ' . $religion->nombre . ' añadida correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error("Error de base de datos al añadir religión.", [
        'entrada_input' => $request->all(),
        'error' => $e->getMessage()
      ]);
      return redirect()->back()->withInput()->with('error', 'Error en la base de datos al crear la religión.');
    } catch (\Exception $e) {
      Log::critical("Error inesperado al añadir religión.", [
        'entrada_input' => $request->all(),
        'error' => $e->getMessage()
      ]);
      return redirect()->back()->withInput()->with('error', 'No se pudo crear: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Religion $religion)
  {
    try {
      $religion->load([
        'fecha_fundacion',
        'fecha_disolucion'
      ]);

      $fundacion = Fecha::get_fecha_string($religion->fundacion);
      $disolucion = Fecha::get_fecha_string($religion->disolucion);

      return view('religiones.show', compact('religion', 'fundacion', 'disolucion'));
    } catch (\Exception $e) {
      Log::error("Error al mostrar religión: " . $e->getMessage());
      return redirect()->route('religiones.index')
        ->with('error', 'Religión no encontrada.');
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Religion $religion)
  {
    try {
      $fundacion = Fecha::find($religion->fundacion_id);
      $disolucion = Fecha::find($religion->disolucion_id);

      return view('religiones.edit', compact('religion', 'fundacion', 'disolucion'));
    } catch (\Exception $e) {
      return redirect()->route('religiones.index')
        ->with('error', 'No se pudo cargar la religión: ' . $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(ReligionRequest $request, Religion $religion)
  {
    $datosValidados = $request->validated();

    try {
      $religion->update_religion($datosValidados);

      return redirect()->route('religiones.index')
        ->with('success', 'Religión ' . $religion->nombre . ' actualizada correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error("Error de base de datos al actualizar religión.", [
        'entrada_input' => $request->all(),
        'error' => $e->getMessage()
      ]);
      return redirect()->back()->withInput()->with('error', 'Error en la base de datos al actualizar la religión.');
    } catch (\Exception $e) {
      Log::critical("Error inesperado al actualizar religión.", [
        'entrada_input' => $request->all(),
        'error' => $e->getMessage()
      ]);
      return redirect()->back()->withInput()->with('error', 'No se pudo actualizar: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Religion $religion)
  {
    $nombre = $religion->nombre;

    try {
      $religion->delete();

      return redirect()->route('religiones.index')
        ->with('success', "La religión '{$nombre}' y sus recursos han sido eliminados.");
    } catch (\Exception $e) {
      Log::error("Error crítico al eliminar religión ID {$religion->id}: " . $e->getMessage());

      return redirect()->route('religiones.index')
        ->with('error', 'No se pudo completar la eliminación debido a un error interno.');
    }
  }
}
