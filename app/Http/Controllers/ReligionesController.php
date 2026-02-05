<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use App\Models\Fecha;
use App\Models\imagen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
  public function store(Request $request)
  {
    $request->validate([
      'nombre'        => 'required|max:255',
      'estatus_legal' => 'required',
      'lema' => 'nullable|max:256',
      'tipo_teismo'   => 'nullable',
      'escudo' => 'nullable|file|image|mimes:jpg,png,gif|max:10240',
      // Componentes de fecha
      'dia_fundacion' => 'nullable|integer|min:1|max:30',
      'mes_fundacion' => 'nullable|integer',
      'anno_fundacion' => 'nullable|integer',
      'dia_disolucion' => 'nullable|integer|min:1|max:30',
      'mes_disolucion' => 'nullable|integer',
      'anno_disolucion' => 'nullable|integer',
    ]);

    try {
      $religion = Religion::store_religion($request);

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
  public function show($id)
  {
    try {
      // Cargamos la religión con sus fechas
      $religion = Religion::with([
        'fecha_fundacion',
        'fecha_disolucion'
      ])->findOrFail($id);

      // Formateamos las fechas para la vista
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
  public function edit($id)
  {
    try {
      //obtener religion a editar
      $religion = Religion::findOrFail($id);

      //obtener fechas
      $fundacion = Fecha::find($religion->fundacion_id);
      $disolucion = Fecha::find($religion->disolucion_id);
    
      return view('religiones.edit', compact('religion', 'fundacion', 'disolucion'));
    } catch (\Exception $e) {
      // Si hay un error de lógica, redirigimos con un mensaje flash
      return redirect()->route('religiones.index')
        ->with('error', 'No se pudo cargar la religión: ' . $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'nombre'        => 'required|max:255',
      'estatus_legal' => 'required',
      'lema' => 'nullable|max:256',
      'tipo_teismo'   => 'nullable',
      'escudo' => 'nullable|file|image|mimes:jpg,png,gif|max:10240',
      // Componentes de fecha
      'dia_fundacion' => 'nullable|integer|min:1|max:30',
      'mes_fundacion' => 'nullable|integer',
      'anno_fundacion' => 'nullable|integer',
      'dia_disolucion' => 'nullable|integer|min:1|max:30',
      'mes_disolucion' => 'nullable|integer',
      'anno_disolucion' => 'nullable|integer',
    ]);

    try {
      $religion = Religion::findOrFail($id);//obtiene la religión en bbdd
      $religion->update_religion($request); //se actualiza con el request

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
  public function destroy(Request $request)
  {
    $id = $request->id_borrar;

    try {
      $religion = Religion::findOrFail($id);
      $nombre = $religion->nombre;

      $religion->delete_religion();

      return redirect()->route('religiones.index')
        ->with('success', "La religión '{$nombre}' y sus recursos han sido eliminados.");
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return redirect()->route('religiones.index')
        ->with('error', 'La religión que intenta eliminar no existe.');
    } catch (\Exception $e) {
      Log::error("Error crítico al eliminar religión ID {$id}: " . $e->getMessage());

      return redirect()->route('religiones.index')
        ->with('error', 'No se pudo completar la eliminación debido a un error interno.');
    }
  }
}
