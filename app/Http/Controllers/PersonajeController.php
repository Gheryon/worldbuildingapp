<?php

namespace App\Http\Controllers;

use App\Models\Especie;
use App\Models\Fecha;
use App\Models\imagen;
use App\Models\personaje;
use Exception;
use Illuminate\Http\Request;
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

    $personajes = personaje::filtrar([
      'orden'  => $orden,
      'especie'   => $especie_id,
      'search' => $terminoBusqueda
    ])->paginate(18);

    //$personajes = personaje::get_personajes($orden, $especie);
    $especies = Especie::get_especies();

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
    $especies = Especie::get_especies();

    return view('personajes.create', compact('especies'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validacion = $request->validate([
      'nombre' => 'required|max:256',
      'nombre_familia' => 'nullable|max:256',
      'causa_fallecimiento' => 'nullable|max:256',
      'sexo' => 'required',
      'select_especie' => 'required',
      'dnacimiento' => 'nullable|integer|min:1|max:30',
      'anacimiento' => 'nullable|integer',
      'dfallecimiento' => 'nullable|integer|min:1|max:30',
      'afallecimiento' => 'nullable|integer',
      'retrato' => 'nullable|file|image|mimes:jpg,png,gif|max:10240',
    ]);

    try {
      // Llamada a la lógica del modelo
      $personaje = personaje::store_personaje($request);

      return redirect()->route('personajes.index')
        ->with('success', 'Personaje ' . $personaje->nombre . 'añadido correctamente.');
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
    $personaje = personaje::with(['especie', 'fechaNacimiento', 'fechaFallecimiento'])
      ->findOrFail($id);

    //obtener fechas
    $nacimiento = Fecha::get_fecha_string($personaje->nacimiento);
    $fallecimiento = Fecha::get_fecha_string($personaje->fallecimiento);

    // Cálculo de la edad, por defecto "Desconocida"
    $edad = "Desconocida";
    if ($personaje->nacimiento) {
      //Si no se ha fallecido, se obtiene la fecha actual del mundo
      if ($personaje->fallecimiento)
        $fechaFin = $personaje->fechaFallecimiento;
      else
        $fechaFin = Fecha::get_fecha_mundo();
      $edad = $personaje->getEdadAttribute($personaje->fechaNacimiento, $fechaFin);
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
      $personaje = personaje::findOrFail($id);

      //obtener todas las especies
      $especies = Especie::get_especies();

      //obtener fechas
      $nacimiento = Fecha::find($personaje->nacimiento);
      $fallecimiento = Fecha::find($personaje->fallecimiento);

      return view('personajes.edit', compact('personaje', 'especies', 'nacimiento', 'fallecimiento'));
    } catch (\Exception $e) {
      // Si hay un error de lógica, redirigimos con un mensaje flash
      return redirect()->route('personajes.index')
        ->with('error', 'No se pudo cargar el personaje: ' . $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'nombre_familia' => 'nullable|max:256',
      'causa_fallecimiento' => 'nullable|max:256',
      'sexo' => 'required',
      'select_especie' => 'required',
      'dnacimiento' => 'nullable|integer|min:1|max:30',
      'anacimiento' => 'nullable|integer',
      'dfallecimiento' => 'nullable|integer|min:1|max:30',
      'afallecimiento' => 'nullable|integer',
      'retrato' => 'file|image|mimes:jpg,png,gif|max:10240',
    ]);

    try {
      $personaje = personaje::findOrFail($id); //obtiene el personaje en bbdd
      $personaje->update_personaje($request); //lo actualiza con el request

      return redirect()->route('personajes.index')
        ->with('success', 'Personaje ' . $personaje->nombre . ' actualizado con éxito.');
    } catch (\Exception $e) {
      Log::error("Error actualizando personaje ID {$id}: " . $e->getMessage());
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
    // Validamos que el ID venga en la petición
    $request->validate([
      'id_borrar' => 'required|integer|exists:personaje,id'
    ]);

    try {
      $personaje = personaje::findOrFail($request->id_borrar);
      $nombre = $personaje->nombre; // Guardamos el nombre para el mensaje

      // Llamamos a la lógica centralizada en el modelo
      $personaje->eliminar_personaje();

      return redirect()->route('personajes.index')
        ->with('success', "El personaje {$nombre} ha sido eliminado correctamente.");
    } catch (\Exception $e) {
      Log::error("Error al eliminar personaje ID {$request->id_borrar}: " . $e->getMessage());

      return redirect()->route('personajes.index')
        ->with('error', 'No se pudo eliminar el personaje. Consulte los logs para más detalles.');
    }
  }
}
