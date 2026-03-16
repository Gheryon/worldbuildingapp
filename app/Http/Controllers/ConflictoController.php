<?php

namespace App\Http\Controllers;

use App\Models\Asentamiento;
use App\Models\Conflicto;
use App\Models\Fecha;
use App\Models\imagen;
use App\Models\Lugar;
use App\Models\Organizacion;
use App\Models\Personaje;
use App\Models\TipoConflicto;
use App\Http\Requests\ConflictoRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConflictoController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $datosValidados = $request->validate([
      'orden' => 'sometimes|string|in:asc,desc', // 'sometimes' permite que no esté presente.
      'tipo'  => 'sometimes|integer|nullable',
      'magia' => 'sometimes|integer|in:0,1,2', // 0 = Todos, 1 = Sí, 2 = No
      'search' => 'sometimes|nullable|string|max:100',
    ], [
      'orden.in' => 'El orden debe ser ascendente (asc) o descendente (desc).',
      'tipo.exists' => 'El tipo seleccionado no es válido.',
      'magia.in' => 'La opción de magia debe ser "Todos", "Sí" o "No".'
    ]);

    // Si la validación falla o el parámetro no está presente, se usan los valores por defecto.
    $orden = $request->get('orden', 'asc');
    $tipo = $request->get('tipo', 0); // 0 es el valor para "todos los tipos".
    $magia = $request->get('magia', 0); // 0 es el valor para "todos".
    $terminoBusqueda = $request->get('search');

    //Obtener conflictos almacenados
    $conflictos = Conflicto::filtrar([
      'orden'  => $orden,
      'tipo'   => $tipo,
      'magia'  => $magia,
      'search' => $terminoBusqueda
    ]) // IMPORTANTE: Incluir siempre las FK (tipo_conflicto_id) para que el 'with' funcione.
      ->select('id', 'nombre', 'tipo_conflicto_id', 'descripcion', 'es_conflicto_magico')
      ->paginate(16)
      ->withQueryString();

    // Obtener todos los tipos de conflictos almacenados
    $tipos = TipoConflicto::orderby('nombre', 'asc')->get();

    return view('conflictos.index', compact('conflictos', 'tipos', 'orden', 'tipo', 'magia', 'terminoBusqueda'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    // Obtener todos los tipos de conflictos almacenados
    $tipos_conflicto = TipoConflicto::orderby('nombre', 'asc')->get();

    // Obtener todos los paises almacenados
    $paises = Organizacion::orderby('nombre', 'asc')->pluck('nombre', 'id');

    // Obtener todos los conflictos almacenados
    $conflictos = Conflicto::orderby('nombre', 'asc')->pluck('nombre', 'id');

    // Obtener todos los personajes almacenados
    $personajes = Personaje::orderby('nombre', 'asc')->pluck('nombre', 'id');

    //Obtener todos los lugares almacenados
    $lugares = Lugar::orderby('nombre', 'asc')->pluck('nombre', 'id') ?? [];

    //Obtener todos los asentamientos almacenados
    $asentamientos = Asentamiento::orderby('nombre', 'asc')->pluck('nombre', 'id') ?? [];

    return view('conflictos.create', compact('tipos_conflicto', 'paises', 'personajes', 'conflictos', 'lugares', 'asentamientos'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(ConflictoRequest $request)
  {
    $datosValidados = $request->validated();
    try {
      // Llamada a la lógica del modelo
      $conflicto = Conflicto::store_conflicto($datosValidados);

      return redirect()->route('conflictos.index')
        ->with('success', 'Conflicto ' . $conflicto->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al añadir conflicto.",
        [
          'entrada_input' => $request->validated(),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el conflicto debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir conflicto.",
        [
          'entrada_input' => $request->validated(),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el conflicto: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      $conflicto = Conflicto::with([
        'tipoConflicto',
        'personajes',
        'organizaciones',
        'conflictoPadre',
      ])->findorfail($id);

      // Obtención de las fechas formateadas para la vista
      $fecha_inicio = Fecha::get_fecha_string($conflicto->fecha_inicio_id);
      $fecha_fin = Fecha::get_fecha_string($conflicto->fecha_fin_id);

      return view('conflictos.show', compact('conflicto', 'fecha_inicio', 'fecha_fin'));
    } catch (\Exception $e) {
      Log::error('Error al mostrar conflicto: ' . $e->getMessage());
      return redirect()->route('conflictos.index')
        ->with('error', 'No se pudo mostrar el conflicto.');
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    try {
      // Obtener todos los tipos de conflictos almacenados
      $tipos_conflicto = TipoConflicto::orderby('nombre', 'asc')->get();

      // Obtener todos los paises almacenados
      $paises = Organizacion::orderby('nombre', 'asc')->pluck('nombre', 'id');

      // Obtener todos los conflictos almacenados
      $conflictos = Conflicto::orderby('nombre', 'asc')->pluck('nombre', 'id');

      // Obtener todos los personajes almacenados
      $personajes = Personaje::orderby('nombre', 'asc')->pluck('nombre', 'id');

      //Obtener todos los lugares almacenados
      $lugares = Lugar::orderby('nombre', 'asc')->pluck('nombre', 'id') ?? [];

      //Obtener todos los asentamientos almacenados
      $asentamientos = Asentamiento::orderby('nombre', 'asc')->pluck('nombre', 'id') ?? [];

      // Obtener la construccion
      $conflicto = Conflicto::with([
        'tipoConflicto',
        'personajes',
        'organizaciones',
        'fechaInicio',
        'fechaFin',
      ])->findorfail($id);

      // Extraemos los IDs actuales por bando para pre-seleccionarlos en la vista
      $personajesAtacantesIds = $conflicto->personajes()->wherePivot('lado', 'atacante')->pluck('personajes.id')->toArray();
      $personajesDefensoresIds = $conflicto->personajes()->wherePivot('lado', 'defensor')->pluck('personajes.id')->toArray();

      $paisesAtacantesIds = $conflicto->organizaciones()->wherePivot('lado', 'atacante')->pluck('organizaciones.id')->toArray();
      $paisesDefensoresIds = $conflicto->organizaciones()->wherePivot('lado', 'defensor')->pluck('organizaciones.id')->toArray();

      return view('conflictos.edit', compact('conflicto', 'tipos_conflicto', 'asentamientos', 'lugares', 'personajes', 'conflictos', 'paises', 'personajesAtacantesIds', 'personajesDefensoresIds', 'paisesAtacantesIds', 'paisesDefensoresIds'));
    } catch (\Exception $e) {
      Log::error("Error al buscar conflicto para editar: " . $e->getMessage());
      return redirect()->route('conflictos.index')
        ->with('error', 'Conflicto no encontrado.');
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(ConflictoRequest $request, $id)
  {
    $datosValidados = $request->validated();
    try {
      $conflicto = Conflicto::findOrFail($id); //obtiene el conflicto en bbdd
      $conflicto->update_conflicto($datosValidados);

      return redirect()->route('conflictos.index')
        ->with('success', 'Conflicto ' . $conflicto->nombre . ' actualizado con éxito.');
    } catch (\Exception $e) {
      Log::error("Error actualizando conflicto ID {$id}: " . $e->getMessage());
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
      $conflicto = Conflicto::findOrFail($request->id_borrar);

      DB::transaction(function () use ($conflicto) {
        $conflicto->delete();
      });

      return redirect()->route('conflictos.index')
        ->with('success', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Exception $e) {
      Log::error('Error al borrar conflicto: ' . $e->getMessage());
      return redirect()->route('conflictos.index')
        ->with('error', 'No se pudo borrar el conflicto.');
    }
  }
}
