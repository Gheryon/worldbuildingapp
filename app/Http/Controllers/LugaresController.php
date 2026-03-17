<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
use App\Models\TipoLugar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LugaresController extends Controller
{
  /**
   * Muestra una lista paginada de lugares almacenados, permitiendo filtrar por tipo y ordenar.
   *
   * Los parámetros de la URL se validan estrictamente para asegurar la integridad de la consulta.
   *
   * @param Request $request Objeto de solicitud inyectado para acceder a los parámetros.
   * @return View La vista con la lista de lugares y los filtros disponibles.
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
    $tipo_id = $request->get('tipo', 0);// 0 es el valor para "todos los tipos".
    $terminoBusqueda = $request->get('search');

    //Obtener lugares almacenados
    $lugares = Lugar::filtrar([
      'orden'  => $orden,
      'tipo'   => $tipo_id,
      'search' => $terminoBusqueda
    ])->paginate(16);

    // Obtener todos los tipos de lugares almacenados
    $tipos = TipoLugar::get_tipos_lugares();

    return view('lugares.index', compact('lugares', 'tipos', 'orden', 'tipo_id', 'terminoBusqueda'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    // Obtener todos los tipos de lugares almacenados
    $tipos = TipoLugar::orderby('nombre', 'asc')->get();

    return view('lugares.create', ['tipos'=>$tipos]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'select_tipo' => 'required|exists:TipoLugar,id',
      'nivel_peligro' => 'nullable|string|max:256',
      'dificultad_acceso' => 'nullable|string|max:50',
    ]);

    try {
      // Llamada a la lógica del modelo
      $lugar = Lugar::store_lugar($request);

      return redirect()->route('lugares.index')
        ->with('success', 'Lugar ' . $lugar->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al añadir lugar.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el lugar debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir lugar.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el lugar: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    $lugar=Lugar::with('tipo')->findOrFail($id);

    return view('lugares.show', compact('lugar'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $lugar=Lugar::findOrFail($id);
    // Obtener todos los tipos de lugares almacenados
    $tipos = TipoLugar::orderby('nombre', 'asc')->get();

    return view('lugares.edit', compact('tipos', 'lugar'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'select_tipo' => 'required|exists:TipoLugar,id',
      'nivel_peligro' => 'nullable|string|max:256',
      'dificultad_acceso' => 'nullable|string|max:50',
    ]);

    try {
      $lugar = Lugar::findOrFail($id); //obtiene el lugar en bbdd
      $lugar->update_lugar($request); //se actualiza con el request

      return redirect()->route('lugares.index')
        ->with('success', 'Lugar ' . $lugar->nombre . ' actualizado con éxito.');
    } catch (\Exception $e) {
      Log::error("Error actualizando lugar ID {$id}: " . $e->getMessage());
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
      $lugar = Lugar::findOrFail($request->id_borrar);

      $lugar->delete_lugar();

      return redirect()->route('lugares.index')
        ->with('success', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Exception $e) {
      Log::error('Error al borrar lugar: ' . $e->getMessage());
      return redirect()->route('lugares.index')
        ->with('error', 'No se pudo borrar el lugar.');
    }
  }
}
