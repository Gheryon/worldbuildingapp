<?php

namespace App\Http\Controllers;

use App\Models\Asentamiento;
use App\Models\Fecha;
use App\Models\imagen;
use App\Models\Organizacion;
use App\Models\Personaje;
use App\Models\tipo_asentamiento;
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
    $tipos_asentamientos = tipo_asentamiento::get_tipos_asentamientos();

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
    $tipos_asentamientos = tipo_asentamiento::orderBy('nombre', 'asc')->get();

    return view('asentamientos.create', compact('paises', 'personajes', 'tipos_asentamientos'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'poblacion' => 'nullable|numeric|min:0',
      'gentilicio' => 'nullable|max:256',
      //selects
      'select_tipo' => 'required|exists:tipo_asentamiento,id',
      'estatus' => 'nullable|string|in:Abandonado,En ruinas,Habitado,Secreto,Olvidado',
      'select_owner' => 'nullable|exists:organizaciones,id',
      'select_gobernante' => 'nullable|exists:personajes,id',
      //fechas
      'dia_fundacion' => 'nullable|integer|min:1|max:30',
      'mes_fundacion' => 'nullable|integer',
      'anno_fundacion' => 'nullable|integer',
      'dia_disolucion' => 'nullable|integer|min:1|max:30',
      'mes_disolucion' => 'nullable|integer',
      'anno_disolucion' => 'nullable|integer',
    ]);

    try {
      // Llamada a la lógica del modelo
      $asentamiento = Asentamiento::store_asentamiento($request);

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
        //'lider',
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
    $tipos_asentamientos = tipo_asentamiento::orderBy('nombre', 'asc')->get();

    return view('asentamientos.edit', compact('asentamiento', 'personajes', 'paises', 'tipos_asentamientos'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'poblacion' => 'nullable|numeric|min:0',
      'gentilicio' => 'nullable|max:256',
      //selects
      'select_tipo' => 'required|exists:tipo_asentamiento,id',
      'estatus' => 'nullable|string|in:Abandonado,En ruinas,Habitado,Secreto,Olvidado',
      'select_owner' => 'nullable|exists:organizaciones,id',
      'select_gobernante' => 'nullable|exists:personajes,id',
      //fechas
      'dia_fundacion' => 'nullable|integer|min:1|max:30',
      'mes_fundacion' => 'nullable|integer',
      'anno_fundacion' => 'nullable|integer',
      'dia_disolucion' => 'nullable|integer|min:1|max:30',
      'mes_disolucion' => 'nullable|integer',
      'anno_disolucion' => 'nullable|integer',
    ]);

    try {
      $asentamiento = Asentamiento::findOrFail($id); //obtiene el asentamiento en bbdd
      $asentamiento->update_asentamiento($request); //se actualiza con el request

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

      $asentamiento->delete_asentamiento();

      return redirect()->route('asentamientos.index')
        ->with('success', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Exception $e) {
      Log::error('Error al borrar asentamiento: ' . $e->getMessage());
      return redirect()->route('asentamientos.index')
        ->with('error', 'No se pudo borrar el asentamiento.');
    }

    if ($request->id_borrar != 0) {
      try {
        $fundacion = DB::scalar("SELECT fundacion FROM asentamientos where id = ?", [$request->id_borrar]);
        $disolucion = DB::scalar("SELECT disolucion FROM asentamientos where id = ?", [$request->id_borrar]);

        //si fundacion/disolucion != 0, la organizacion tiene fecha establecida, hay que borrar
        if ($fundacion != 0) {
          Fecha::destroy($fundacion);
        }
        if ($disolucion != 0) {
          Fecha::destroy($disolucion);
        }

        //borrado de las imagenes que pueda haber de summernote
        $imagenes = DB::table('imagenes')
          ->select('id', 'nombre')
          ->where('table_owner', '=', 'asentamientos')
          ->where('owner', '=', $request->id_borrar)->get();

        foreach ($imagenes as $imagen) {
          if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
            unlink(public_path("/storage/imagenes/" . $imagen->nombre));
            //Storage::delete(asset($imagen->nombre));
          }
          imagen::destroy($imagen->id);
        }
        Asentamiento::destroy($request->id_borrar);
        return redirect()->route('asentamientos.index')->with('message', $request->nombre_borrado . ' borrado correctamente.');
      } catch (\Illuminate\Database\QueryException $excepcion) {
        Log::error('AsentamientoController->destroy: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
        return redirect()->route('asentamientos.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo borrar.');
      } catch (Exception $excepcion) {
        Log::error('AsentamientoController->destroy: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
        return redirect()->route('asentamientos.index')->with('error', $excepcion->getMessage());
      }
    } else {
      return redirect()->route('asentamientos.index')->with('error', 'Error no se pudo borrar.');
    }
  }
}
