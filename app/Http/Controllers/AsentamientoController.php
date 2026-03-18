<?php

namespace App\Http\Controllers;

use App\Models\Asentamiento;
use App\Models\Fecha;
use App\Models\Organizacion;
use App\Models\Personaje;
use App\Models\TipoAsentamiento;
use App\Http\Requests\AsentamientoRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsentamientoController extends Controller
{
  /**
   * Muestra una lista paginada de asentamientos almacenados, permitiendo filtrar por tipo y ordenar.
   *
   * Los parámetros de la URL se validan estrictamente para asegurar la integridad de la consulta.
   *
   * @param Request $request Objeto de solicitud inyectado para acceder a los parámetros.
   * @return View La vista con la lista de asentamientos y los filtros disponibles.
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
    $orden = $datosValidados['orden'] ?? 'asc';
    $tipo_id = $datosValidados['tipo'] ?? 0; // 0 es el valor para "todos los tipos de organización".
    $terminoBusqueda = $datosValidados['search'] ?? null;

    //Obtener asentamientos almacenados
    $asentamientos = Asentamiento::filtrar([
      'orden'  => $orden,
      'tipo'   => $tipo_id,
      'search' => $terminoBusqueda
    ])->paginate(18);

    // Obtener todos los tipos de asentamientos almacenados
    $tipos_asentamientos = TipoAsentamiento::get_tipos_asentamientos();

    return view('asentamientos.index', compact('asentamientos', 'tipos_asentamientos', 'orden', 'tipo_id', 'terminoBusqueda'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    // Obtener todos los paises almacenados, sólo id y nombre
    $paises = Organizacion::orderBy('nombre', 'asc')->pluck('nombre', 'id');

    // Obtener todos los personajes almacenados, sólo id y nombre
    $personajes = Personaje::orderBy('nombre', 'asc')->pluck('nombre', 'id');

    // Obtener todos los tipos de asentamiento almacenados
    $tipos_asentamientos = TipoAsentamiento::orderBy('nombre', 'asc')->get();

    return view('asentamientos.create', compact('paises', 'personajes', 'tipos_asentamientos'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(AsentamientoRequest $request)
  {
    $datosValidados = $request->validated();

    try {
      // Llamada a la lógica del modelo
      $asentamiento = Asentamiento::store_asentamiento($datosValidados);

      return redirect()->route('asentamientos.index')
        ->with('success', 'Asentamiento ' . $asentamiento->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al añadir asentamiento.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el asentamiento debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir asentamiento.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el asentamiento: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      // Cargamos el asentamiento con sus fechas, tipo, religiones y ruler
      $asentamiento = Asentamiento::with([
        'tipo',
        'controlado_por',
      ])->findOrFail($id);

      // Formateamos las fechas para la vista
      $fundacion = Fecha::get_fecha_string($asentamiento->fundacion_id);
      $disolucion = Fecha::get_fecha_string($asentamiento->disolucion_id);

      return view('asentamientos.show', compact('asentamiento', 'fundacion', 'disolucion'));
    } catch (\Exception $e) {
      Log::error("Error al mostrar asentamiento: " . $e->getMessage());
      return redirect()->route('asentamientos.index')
        ->with('error', 'Organización no encontrada.');
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    // Obtener el asentamiento a editar
    $asentamiento = Asentamiento::with(['fecha_fundacion', 'fecha_disolucion'])->findOrFail($id);

    // Obtener todos los paises almacenados, sólo id y nombre
    $paises = Organizacion::orderBy('nombre', 'asc')->pluck('nombre', 'id');

    // Obtener todos los personajes almacenados, sólo id y nombre
    $personajes = Personaje::orderBy('nombre', 'asc')->pluck('nombre', 'id');

    // Obtener todos los tipos de asentamiento almacenados
    $tipos_asentamientos = TipoAsentamiento::orderBy('nombre', 'asc')->get();

    return view('asentamientos.edit', compact('asentamiento', 'personajes', 'paises', 'tipos_asentamientos'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(AsentamientoRequest $request, $id)
  {
    $datosValidados = $request->validated();

    try {
      $asentamiento = Asentamiento::findOrFail($id); //obtiene el asentamiento en bbdd
      $asentamiento->update_asentamiento($datosValidados); //se actualiza con los datos validados

      return redirect()->route('asentamientos.index')
        ->with('success', 'Asentamiento ' . $asentamiento->nombre . ' actualizado con éxito.');
    } catch (\Exception $e) {
      Log::error("Error actualizando asentamiento ID {$id}: " . $e->getMessage());
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
      $asentamiento = Asentamiento::findOrFail($request->id_borrar);

      DB::transaction(function () use ($asentamiento) {
        $asentamiento->delete();
      });

      return redirect()->route('asentamientos.index')
        ->with('success', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Exception $e) {
      Log::error('Error al borrar asentamiento: ' . $e->getMessage());
      return redirect()->route('asentamientos.index')
        ->with('error', 'No se pudo borrar el asentamiento.');
    }
  }
}
