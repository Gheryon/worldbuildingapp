<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use App\Models\tipo_organizacion;
use App\Models\Fecha;
use App\Models\personaje;
use App\Models\Religion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Ramsey\Uuid\v1;

class OrganizacionController extends Controller
{
  /**
   * Muestra una lista paginada de organizaciones almacenadas, permitiendo filtrar por especie y ordenar.
   *
   * Los parámetros de la URL se validan estrictamente para asegurar la integridad de la consulta.
   *
   * @param Request $request Objeto de solicitud inyectado para acceder a los parámetros.
   * @return View La vista con la lista de organizaciones y los filtros disponibles.
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

    //Obtener organizaciones almacenadas
    $organizaciones = Organizacion::filtrar([
      'orden'  => $orden,
      'tipo'   => $tipo_id,
      'search' => $terminoBusqueda
    ])->paginate(18);

    // Obtener todos los tipos de organizacion almacenados
    $tipos_organizacion = tipo_organizacion::get_tipos_organizaciones();

    return view('organizaciones.index', compact('organizaciones', 'tipos_organizacion', 'orden', 'tipo_id', 'terminoBusqueda'));
  }

  /**
   * Mostrar formulario para crear una nueva organización.
   */
  public function create()
  {
    // Obtener todos los tipos de organizacion almacenados
    $tipo_organizacion = tipo_organizacion::get_tipos_organizaciones();

    // Obtener todos los personajes almacenados
    $personajes = personaje::get_personajes_id_nombre();

    //obtener todas las religiones
    $religiones = Religion::get_religiones();

    // Obtener todos los paises almacenados, sólo id y nombre
    $paises = Organizacion::get_organizaciones_id_nombre();

    return view('organizaciones.create', compact('tipo_organizacion', 'paises', 'personajes', 'religiones'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validacion = $request->validate([
      'nombre' => 'required|max:255',
      'lema' => 'nullable|max:512',
      'gentilicio' => 'nullable|max:128',
      'capital' => 'nullable|max:128',
      'escudo' => 'file|image|mimes:jpg,png,gif|max:10240',
      'dfundacion' => 'nullable|integer|min:1|max:30',
      'ddisolucion' => 'nullable|integer|min:1|max:30',
      'religiones' => 'nullable|array',
      'religiones.*' => 'exists:religiones,id',
      'select_tipo' => 'required|exists:tipo_organizacion,id',
      'select_ruler' => 'nullable',
      'select_ruler.*' => 'exists:personajes,id',
      'select_owner' => 'nullable',
      'select_owner.*' => 'exists:organizaciones,id',
    ]);

    try {
      // Llamada a la lógica del modelo
      $organizacion = Organizacion::store_organizacion($request);

      return redirect()->route('organizaciones.index')
        ->with('success', 'Organización ' . $organizacion->nombre . ' añadida correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al añadir organización.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear la organización debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir organización.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear la organización: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      // Cargamos la organización con sus fechas, tipo, religiones y ruler
      $organizacion = Organizacion::with([
        'fecha_fundacion',
        'fecha_disolucion',
        'religiones',
        'tipo',
        'ruler',
        'subordinates'=> function ($query) {
                $query->orderBy('nombre', 'asc');
            }
      ])->findOrFail($id);

      // Formateamos las fechas para la vista
      $fundacion = Fecha::get_fecha_string($organizacion->fundacion);
      $disolucion = Fecha::get_fecha_string($organizacion->disolucion);

      return view('organizaciones.show', compact('organizacion', 'fundacion', 'disolucion'));
    } catch (\Exception $e) {
      Log::error("Error al mostrar organización: " . $e->getMessage());
      return redirect()->route('organizaciones.index')
        ->with('error', 'Organización no encontrada.');
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    try {
      // Obtener la organizacion a editar
      $organizacion = Organizacion::findOrFail($id);

      // Obtener todos los tipos de organizacion almacenados
      $tipo_organizacion = tipo_organizacion::get_tipos_organizaciones();

      // Obtener todos los personajes almacenados
      $personajes = personaje::get_personajes_id_nombre();

      // Obtener todos los paises almacenados
      $paises = Organizacion::get_organizaciones_id_nombre();

      //obtener todas las religiones
      $religiones = Religion::get_religiones();

      //Obtener religiones presentes en la organizacion
      $religiones_p = Organizacion::get_religiones_presentes($id);

      //obtener fechas
      $fundacion = Fecha::find($organizacion->fundacion);
      $disolucion = Fecha::find($organizacion->disolucion);

      return view('organizaciones.edit', compact('organizacion', 'fundacion', 'disolucion', 'tipo_organizacion', 'personajes', 'paises', 'religiones', 'religiones_p'));
    } catch (\Exception $e) {
      // Si hay un error de lógica, redirigimos con un mensaje flash
      return redirect()->route('organizaciones.index')
        ->with('error', 'No se pudo cargar la organización: ' . $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $validacion = $request->validate([
      'nombre' => 'required|max:255',
      'lema' => 'nullable|max:512',
      'gentilicio' => 'nullable|max:128',
      'capital' => 'nullable|max:128',
      'escudo' => 'sometimes|file|image|mimes:jpg,png,gif|max:10240',
      'dfundacion' => 'nullable|integer|min:1|max:30',
      'ddisolucion' => 'nullable|integer|min:1|max:30',
      'religiones' => 'nullable|array',
      'religiones.*' => 'exists:religiones,id',
      'select_tipo' => 'required|exists:tipo_organizacion,id',
      'select_ruler' => 'nullable',
      'select_ruler.*' => 'exists:personajes,id',
      'select_owner' => 'nullable',
      'select_owner.*' => 'exists:organizaciones,id',
    ]);

    try {
      $organizacion = Organizacion::findOrFail($id); //obtiene la organizacion en bbdd
      $organizacion->update_organizacion($request); //se actualiza con el request

      return redirect()->route('organizaciones.index')
        ->with('success', 'Organización ' . $organizacion->nombre . ' actualizada con éxito.');
    } catch (\Exception $e) {
      Log::error("Error actualizando organización ID {$id}: " . $e->getMessage());
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
      $organizacion = Organizacion::findOrFail($request->id_borrar);

      $organizacion->delete_organizacion();

      return redirect()->route('organizaciones.index')
        ->with('success', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Exception $e) {
      Log::error('Error al borrar organización: ' . $e->getMessage());
      return redirect()->route('organizaciones.index')
        ->with('error', 'No se pudo borrar la organización.');
    }
  }
}
