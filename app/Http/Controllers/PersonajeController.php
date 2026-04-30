<?php

namespace App\Http\Controllers;

use App\Models\Especie;
use App\Models\Fecha;
use App\Models\Personaje;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\PersonajeRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonajeController extends Controller
{
  /**
   * Muestra una lista paginada de personajes, permitiendo filtrar por especie y ordenar.
   *
   * Los parámetros de la URL se validan estrictamente para asegurar la integridad de la consulta.
   *
   * @param Request $request Objeto de solicitud inyectado para acceder a los parámetros.
   * @return View La vista con la lista de personajes y los filtros disponibles.
   */
  public function index(Request $request)
  {
    $datosValidados = $request->validate([
      'orden' => 'sometimes|string|in:asc,desc', // 'sometimes' permite que no esté presente.
      'especie'  => 'sometimes|integer|nullable',
      'search' => 'sometimes|nullable|string|max:100',
    ], [
      'orden.in' => 'El orden debe ser ascendente (asc) o descendente (desc).',
      'especie.exists' => 'La especie seleccionada no es válida.',
    ]);

    // Si la validación falla o el parámetro no está presente, se usan los valores por defecto.
    $orden = $datosValidados['orden'] ?? 'asc';
    $especie_id = $datosValidados['especie'] ?? 0; // 0 es el valor para "todas las especies".
    $terminoBusqueda = $datosValidados['search'] ?? null;

    $personajes = Personaje::filtrar([
      'orden'  => $orden,
      'especie'   => $especie_id,
      'search' => $terminoBusqueda
    ]) // IMPORTANTE: Incluir siempre las FK (especie_id) para que el 'with' funcione.
      ->select('id', 'nombre', 'retrato', 'especie_id', 'sexo')
      ->paginate(18)
      ->withQueryString();

    $especies = Especie::orderBy('nombre', 'asc')->pluck('nombre', 'id');

    return view('personajes.index', compact('personajes', 'especies', 'orden', 'especie_id', 'terminoBusqueda'));
  }

  /**
   * Muestra la vista para crear un nuevo personaje.
   *
   * @return View La vista con el formulario para crear un nuevo personaje.
   */
  public function create()
  {
    //obtener todas las especies
    $especies = Especie::orderBy('nombre', 'asc')->pluck('nombre', 'id');

    return view('personajes.create', compact('especies'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(PersonajeRequest $request)
  {
    $datosValidados = $request->validated();

    try {
      // Llamada a la lógica del modelo
      $personaje = Personaje::store_personaje($datosValidados);

      return redirect()->route('personajes.index')
        ->with('success', 'Personaje ' . $personaje->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al añadir personaje.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el personaje debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir personaje.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el personaje: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    // Cargamos el personaje con sus relaciones para evitar el problema N+1
    $personaje = Personaje::with(['especie'])
      ->findOrFail($id);

    //obtener fechas en formato string
    $nacimiento = Fecha::get_fecha_string($personaje->nacimiento_id);
    $fallecimiento = Fecha::get_fecha_string($personaje->fallecimiento_id);

    // Cálculo de la edad, por defecto "Desconocida"
    $edad = "Desconocida";
    if ($personaje->nacimiento_id) {
      //Si no se ha fallecido, se obtiene la fecha actual del mundo
      if ($personaje->fallecimiento_id)
        $fechaFin = Fecha::find($personaje->fallecimiento_id);
      else
        $fechaFin = Fecha::get_fecha_mundo();
      $edad = $personaje->getEdadAttribute(Fecha::find($personaje->nacimiento_id), $fechaFin);
    }

    return view('personajes.show', [
      'personaje' => $personaje,
      'especie' => $personaje->especie->nombre ?? 'Desconocida',
      'nacimiento' => $nacimiento,
      'fallecimiento' => $fallecimiento,
      'edad' => $edad
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    try {
      //obtener personaje
      $personaje = Personaje::with(['fecha_nacimiento', 'fecha_fallecimiento'])->findOrFail($id);

      //obtener todas las especies
      $especies = Especie::orderBy('nombre', 'asc')->pluck('nombre', 'id');

      return view('personajes.edit', compact('personaje', 'especies'));
    } catch (\Exception $e) {
      // Si hay un error de lógica, redirigimos con un mensaje flash
      return redirect()->route('personajes.index')
        ->with('error', 'No se pudo cargar el personaje: ' . $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(PersonajeRequest $request, Personaje $personaje)
  {
    $datosValidados = $request->validated();

    try {
      $personaje->update_personaje($datosValidados);

      return redirect()->route('personajes.index')
        ->with('success', 'Personaje ' . $personaje->nombre . ' actualizado con éxito.');
    } catch (\Exception $e) {
      Log::error("Error actualizando personaje ID {$personaje->id}: " . $e->getMessage());
      return redirect()->back()
        ->withInput()
        ->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Personaje $personaje)
  {
    $nombre = $personaje->nombre;

    try {
      DB::transaction(function () use ($personaje) {
        $personaje->delete();
      });

      return redirect()->route('personajes.index')
        ->with('success', "El personaje {$nombre} ha sido eliminado correctamente.");
    } catch (\Exception $e) {
      Log::error("Error al eliminar personaje ID {$personaje->id}: " . $e->getMessage());

      return redirect()->route('personajes.index')
        ->with('error', 'No se pudo eliminar el personaje. Consulte los logs para más detalles.');
    }
  }
}
