<?php

namespace App\Http\Controllers;

use App\Models\articulo;
use App\Models\imagen;
use App\Models\personaje;
use App\Http\Controllers\ImagenController;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArticuloController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $datosValidados = $request->validate([
      'orden' => 'sometimes|string|in:asc,desc', // 'sometimes' permite que no esté presente.
      'fecha' => 'sometimes|string|in:asc,desc', // 'sometimes' permite que no esté presente.
      'tipo'  => 'sometimes|string|nullable',
      'search' => 'sometimes|nullable|string|max:100',
    ], [
      'orden.in' => 'El orden debe ser ascendente (asc) o descendente (desc).',
      'fecha.in' => 'El orden debe ser ascendente (asc) o descendente (desc).',
    ]);

    // Si la validación falla o el parámetro no está presente, se usan los valores por defecto.
    $orden = $datosValidados['orden'] ?? 'asc';
    $fecha = $datosValidados['fecha'] ?? null; // Si no se especifica, no se ordena por fecha.
    $tipo = $datosValidados['tipo'] ?? 'all';
    $terminoBusqueda = $datosValidados['search'] ?? null;

    $articulos = articulo::filtrar([
      'orden'  => $orden,
      'fecha'  => $fecha,
      'tipo'   => $tipo,
      'search' => $terminoBusqueda
    ])->paginate(50);

    return view('articulos.index', compact('articulos', 'orden', 'fecha', 'tipo', 'terminoBusqueda'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('articulos.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $validacion = $request->validate([
      'nombre' => 'required|max:256',
      'tipo' => 'required',
      'contenido' => 'required'
    ]);

    try {
      // Llamada a la lógica del modelo
      $articulo = articulo::store_articulo($request);

      return redirect()->route('articulos.index')
        ->with('success', 'Artículo ' . $articulo->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al añadir artículo.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el artículo debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir artículo.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el artículo: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      // Cargamos el articulo 
      $articulo = Articulo::findOrFail($id);

      return view('articulos.show', compact('articulo'));
    } catch (\Exception $e) {
      Log::error("Error al mostrar articulo: " . $e->getMessage());
      return redirect()->route('articulos.index')
        ->with('error', 'Articulo no encontrado.');
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    try {
      // Cargamos el articulo 
      $articulo = Articulo::findOrFail($id);

      return view('articulos.edit', compact('articulo'));
    } catch (\Exception $e) {
      Log::error("Error al obtener articulo: " . $e->getMessage());
      return redirect()->route('articulos.index')
        ->with('error', 'Articulo no encontrado.');
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $validacion = $request->validate([
      'nombre' => 'required|max:256',
      'tipo' => 'required',
      'contenido' => 'required'
    ]);

    try {
      // Llamada a la lógica del modelo
      $articulo = articulo::findOrFail($id);
      $articulo->update_articulo($request);

      return redirect()->route('articulos.index')
        ->with('success', 'Artículo ' . $articulo->nombre . ' editado correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al editar artículo.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el artículo debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir artículo.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el artículo: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    // Validamos que el ID venga en la petición
    $request->validate([
      'id_borrar' => 'required|integer|exists:articulos_genericos,id'
    ]);

    try {
      $articulo = articulo::findOrFail($request->id_borrar);
      $nombre = $articulo->nombre; // Guardamos el nombre para el mensaje

      // Llamamos a la lógica centralizada en el modelo
      $articulo->eliminar_articulo();

      return redirect()->route('articulos.index')
        ->with('success', "El articulo {$nombre} ha sido eliminado correctamente.");
    } catch (\Exception $e) {
      Log::error("Error al eliminar articulo ID {$request->id_borrar}: " . $e->getMessage());

      return redirect()->route('articulos.index')
        ->with('error', 'No se pudo eliminar el articulo. Consulte los logs para más detalles.');
    }
  }

  /**
   * Display a listing of the resource.
   */
  public function index_relatos(Request $request)
  {
    $datosValidados = $request->validate([
      'orden' => 'sometimes|string|in:asc,desc', // 'sometimes' permite que no esté presente.
      'fecha' => 'sometimes|string|in:asc,desc', // 'sometimes' permite que no esté presente.
      'search' => 'sometimes|nullable|string|max:100',
    ], [
      'orden.in' => 'El orden debe ser ascendente (asc) o descendente (desc).',
      'fecha.in' => 'El orden debe ser ascendente (asc) o descendente (desc).',
    ]);

    // Si la validación falla o el parámetro no está presente, se usan los valores por defecto.
    $orden = $datosValidados['orden'] ?? 'asc';
    $fecha = $datosValidados['fecha'] ?? null; // Si no se especifica, no se ordena por fecha.
    $tipo = $datosValidados['tipo'] ?? 'all';
    $terminoBusqueda = $datosValidados['search'] ?? null;

    $articulos = articulo::filtrar([
      'orden'  => $orden,
      'fecha'  => $fecha,
      'tipo'   => 'Relato', // Solo mostramos relatos
      'search' => $terminoBusqueda
    ])->paginate(50);

    return view('relatos.index', compact('articulos', 'orden', 'fecha', 'tipo', 'terminoBusqueda'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create_relato()
  {
    //Obtener todos los personajes almacenados
    $personajes = personaje::orderBy('nombre', 'asc')->pluck('nombre', 'id');

    return view('relatos.create', compact('personajes'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store_relato(Request $request)
  {
    $request->validate([
      'nombre'    => 'required|string|max:256',
      'contenido' => 'required',
      'personajes' => 'nullable|array',
      'personajes.*' => 'exists:personajes,id',
    ]);

    try {
      // Forzamos el tipo a Relato por seguridad
      $request->merge(['tipo' => 'Relato']);

      // Llamada a la lógica del modelo
      $articulo = articulo::store_articulo($request);

      return redirect()->route('relatos.index')
        ->with('success', 'Relato ' . $articulo->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al añadir relato.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el relato debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir relato.",
        [
          'entrada_input' => $request,
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el relato: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show_relato($id)
  {
    try {
      // Buscamos el artículo asegurándonos de que sea un 'Relato' 
      // y cargamos sus personajes relacionados en una sola query
      $articulo = articulo::where('tipo', 'Relato')
        ->with('personajes_relevantes')
        ->findOrFail($id);

      return view('articulos.show', compact('articulo'));
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      Log::error("Relato no encontrado ID {$id}: " . $e->getMessage());
      return redirect()->route('relatos.index')
        ->with('error', 'El relato solicitado no existe.');
    } catch (\Exception $e) {
      Log::critical("Error al mostrar relato ID {$id}: " . $e->getMessage());
      return redirect()->route('relatos.index')
        ->with('error', 'Ocurrió un error inesperado al cargar el relato.');
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit_relato($id)
  {
    try {
      // Cargamos el relato 
      $relato = Articulo::with('personajes_relevantes')->findOrFail($id);

      //Obtener todos los personajes almacenados
      $personajes = personaje::orderBy('nombre', 'asc')->pluck('nombre', 'id');

      return view('relatos.edit', compact('relato', 'personajes'));
    } catch (\Exception $e) {
      Log::error("Error al obtener relato: " . $e->getMessage());
      return redirect()->route('relatos.index')
        ->with('error', 'Relato no encontrado.');
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_relato(Request $request, $id)
  {
    $request->validate([
      'nombre'    => 'required|string|max:256',
      'contenido' => 'required',
      'personajes' => 'nullable|array',
      'personajes.*' => 'exists:personajes,id',
    ]);

    try {
      $relato = articulo::findOrFail($id);

      // Forzamos el tipo a Relato por seguridad
      $request->merge(['tipo' => 'Relato']);

      $relato->update_articulo($request);

      return redirect()->route('relatos.index')
        ->with('success', "Relato '{$relato->nombre}' actualizado con éxito.");
    } catch (\Exception $e) {
      Log::error("Error al actualizar relato ID {$id}: " . $e->getMessage());
      return redirect()->back()
        ->withInput()
        ->with('error', 'Error al guardar los cambios: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy_relato(Request $request)
  {
    // Validamos que el ID exista y pertenezca a un relato
    $request->validate([
      'id_borrar' => 'required|integer|exists:articulos_genericos,id'
    ]);

    try {
      $relato = articulo::findOrFail($request->id_borrar);
      $nombre = $relato->nombre;

      // Ejecutamos la lógica encapsulada en el modelo
      $relato->eliminar_articulo();

      return redirect()->route('relatos.index')
        ->with('success', "El relato '{$nombre}' ha sido eliminado correctamente.");
    } catch (\Exception $e) {
      Log::error("Error al eliminar relato ID {$request->id_borrar}: " . $e->getMessage());

      return redirect()->route('relatos.index')
        ->with('error', 'No se pudo eliminar el relato debido a un error interno.');
    }
  }
}
