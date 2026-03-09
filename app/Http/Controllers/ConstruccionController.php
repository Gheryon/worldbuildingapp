<?php

namespace App\Http\Controllers;

use App\Models\Asentamiento;
use App\Models\Construccion;
use App\Models\Fecha;
use App\Models\imagen;
use App\Models\TipoConstruccion;
use App\Http\Requests\ConstruccionRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConstruccionController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $datosValidados = $request->validate([
      'orden' => 'sometimes|string|in:asc,desc', // 'sometimes' permite que no esté presente.
      'tipo'  => 'sometimes|integer|nullable',
      'search' => 'sometimes|nullable|string|max:100',
    ], [
      'orden.in' => 'El orden debe ser ascendente (asc) o descendente (desc).',
      'tipo.exists' => 'El tipo seleccionado no es válido.',
    ]);

    // Si la validación falla o el parámetro no está presente, se usan los valores por defecto.
    $orden = $request->get('orden', 'asc');
    $tipo_id = $request->get('tipo', 0); // 0 es el valor para "todos los tipos".
    $terminoBusqueda = $request->get('search');

    //Obtener construcciones almacenadas
    $construcciones = Construccion::filtrar([
      'orden'  => $orden,
      'tipo'   => $tipo_id,
      'search' => $terminoBusqueda
    ])->paginate(16);

    // Obtener todos los tipos de construcciones almacenadas
    $tipos = TipoConstruccion::orderby('nombre', 'asc')->get();

    return view('construcciones.index', compact('construcciones', 'tipos', 'orden', 'tipo_id', 'terminoBusqueda'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    // Obtener todos los tipos de construcciones almacenados
    $tipos = TipoConstruccion::orderby('nombre', 'asc')->get();

    // Obtener todos los asentamientos almacenados
    $asentamientos = Asentamiento::orderby('nombre', 'asc')->pluck('nombre', 'id');

    return view('construcciones.create', compact('tipos', 'asentamientos'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(ConstruccionRequest $request)
  {
    $datosValidados=$request->validated();
    try {
      // Llamada a la lógica del modelo
      $construccion = Construccion::store_construccion($datosValidados);

      return redirect()->route('construcciones.index')
        ->with('success', 'Construcción ' . $construccion->nombre . ' añadida correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al añadir construccion.",
        [
          'entrada_input' => $request->validated(),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear la construccion debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir construccion.",
        [
          'entrada_input' => $request->validated(),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear la construccion: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      $construccion = Construccion::with([
        'tipo',
        'asentamiento',
      ])->findorfail($id);

      // Obtención de las fechas formateadas para la vista
      $fecha_construccion = Fecha::get_fecha_string($construccion->fecha_construccion_id);
      $fecha_destruccion = Fecha::get_fecha_string($construccion->fecha_destruccion_id);

      return view('construcciones.show', compact('construccion', 'fecha_construccion', 'fecha_destruccion'));
    } catch (\Exception $e) {
      Log::error('Error al mostrar construccion: ' . $e->getMessage());
      return redirect()->route('construcciones.index')
        ->with('error', 'No se pudo mostrar la construccion.');
    }
  }
  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    try {
      // Obtener todos los tipos de construcciones almacenados
      $tipos = TipoConstruccion::orderby('nombre', 'asc')->get();

      // Obtener todos los asentamientos almacenados
      $asentamientos = Asentamiento::orderby('nombre', 'asc')->pluck('nombre', 'id');

      // Obtener la construccion
      $construccion = Construccion::with([
        'tipo',
        'asentamiento',
        'fechaConstruccion',
        'fechaDestruccion',
      ])->findorfail($id);

      return view('construcciones.edit', compact('construccion', 'tipos', 'asentamientos'));
    } catch (\Exception $e) {
      Log::error("Error al buscar construccion para editar: " . $e->getMessage());
      return redirect()->route('construcciones.index')
        ->with('error', 'Construcción no encontrada.');
    }
  }
  /**
   * Update the specified resource in storage.
   */
  public function update(ConstruccionRequest $request, $id)
  {
    $datosValidados=$request->validated();
    try {
      $construccion = Construccion::findOrFail($id); //obtiene la construccion en bbdd
      $construccion->update_construccion($datosValidados);

      return redirect()->route('construcciones.index')
        ->with('success', 'Construccion ' . $construccion->nombre . ' actualizada con éxito.');
    } catch (\Exception $e) {
      Log::error("Error actualizando construccion ID {$id}: " . $e->getMessage());
      return redirect()->back()
        ->withInput()
        ->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    try {
      $construccion = Construccion::findOrFail($request->id_borrar);

      $construccion->delete();

      return redirect()->route('construcciones.index')
        ->with('success', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Exception $e) {
      Log::error('Error al borrar construccion: ' . $e->getMessage());
      return redirect()->route('construcciones.index')
        ->with('error', 'No se pudo borrar la construccion.');
    }
  }
}
