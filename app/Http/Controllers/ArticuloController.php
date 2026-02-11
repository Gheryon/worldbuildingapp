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
      $articulo=articulo::findOrFail($id);
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
  public function index_relatos($orden = 'asc')
  {
    // Obtener todos los relatos almacenados
    $articulos = articulo::get_relatos();

    return view('relatos.index', ['relatos' => $articulos, 'orden' => $orden]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create_relato()
  {
    //Obtener todos los personajes almacenados
    $personajes = personaje::get_personajes_id_nombre();

    return view('relatos.create')->with('personajes', $personajes);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store_relato(Request $request)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'contenido' => 'required'
    ]);

    $articulo = new articulo();
    $articulo->nombre = $request->nombre;
    $articulo->tipo = 'relato';
    $content = $request->contenido;

    try {
      $articulo->save();
      $id_articulo = DB::scalar("SELECT MAX(id_articulo) as id FROM articulosgenericos");

      $articulo->contenido = app(ImagenController::class)->store_for_summernote($content, "articulos", $id_articulo);

      if ($request->filled('personajes')) {
        $personajes = $request->input('personajes');
        try {
          foreach ($personajes as $personaje) {
            DB::table('personajes_relevantes')->insert([
              'relato' => $id_articulo,
              'personaje' => $personaje,
            ]);
          }
        } catch (\Illuminate\Database\QueryException $excepcion) {
        } catch (Exception $excepcion) {
        }
      }
      $articulo->save();
      return redirect()->route('relatos')->with('message', 'Historia ' . $articulo->nombre . ' añadida correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('ArticuloController->store_relato: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('relatos')->with('error', 'Se produjo un problema en la base de datos, no se pudo añadir.');
    } catch (Exception $excepcion) {
      Log::error('ArticuloController->store_relato: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('relatos')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show_relato($id)
  {
    $articulo = articulo::get_articulo($id);
    if ($articulo['error'] ?? false) {
      return redirect()->route('relatos')->with('error', $articulo['error']['error']);
    }

    try {
      $personajes_r = DB::select('SELECT personaje.id, personaje.Nombre as nombre FROM personajes_relevantes JOIN personaje ON personajes_relevantes.personaje=personaje.id WHERE relato = ?', [$id]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $personajes_r = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $personajes_r = ['error' => ['error' => $excepcion->getMessage()]];
    }

    return view('relatos.show', ['relato' => $articulo, 'personajes' => $personajes_r]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit_relato($id)
  {
    $articulo = articulo::get_articulo($id);
    if ($articulo['error'] ?? false) {
      return redirect()->route('relatos')->with('error', $articulo['error']['error']);
    }

    // Obtener todos los personajes almacenados
    $personajes = personaje::get_personajes_id_nombre();

    try {
      $personajes_r = DB::table('personajes_relevantes')->select('personaje')->where('relato', '=', $id)->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('ArticuloController->edit_relato: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $personajes_r = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      Log::error('ArticuloController->edit_relato: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $personajes_r = ['error' => ['error' => $excepcion->getMessage()]];
    }

    return view('relatos.edit', ['relato' => $articulo, 'personajes' => $personajes, 'personajes_r' => $personajes_r]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_relato(Request $request, $id)
  {
    $request->validate([
      'nombre' => 'required|string|max:256',
      'contenido' => 'required',
    ]);

    $relato = articulo::get_articulo($id);
    if ($relato['error'] ?? false) {
      return redirect()->route('relatos')->with('error', $relato['error']['error']);
    }

    $relato->nombre = $request->nombre;
    $content = $request->contenido;
    $relato->contenido = app(ImagenController::class)->update_for_summernote($content, "articulos", $id);

    if ($request->filled('personajes')) {
      // Eliminar los personajes relevantes existentes
      DB::table('personajes_relevantes')->where('relato', '=', $id)->delete();
      $personajes = $request->input('personajes');
      try {
        // Insertar los nuevos personajes relevantes
        foreach ($personajes as $personajeId) {
          DB::table('personajes_relevantes')->insert([
            'relato' => $id,
            'personaje' => $personajeId,
          ]);
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
      } catch (Exception $excepcion) {
      }
    }

    try {
      $relato->save();
      return redirect()->route('relatos')->with('message', $relato->nombre . ' editado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('ArticuloController->update_relato: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('relatos')->with('error', 'Se produjo un problema en la base de datos, no se pudo actualizar.');
    } catch (Exception $excepcion) {
      Log::error('ArticuloController->update_relato: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('relatos')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy_relato(Request $request)
  {
    try {
      $imagenes = DB::table('imagenes')
        ->select('id', 'nombre')
        ->where('table_owner', '=', 'articulos')
        ->where('owner', '=', $request->id_borrar)->get();

      foreach ($imagenes as $imagen) {
        if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
          unlink(public_path("/storage/imagenes/" . $imagen->nombre));
          //Storage::delete(asset($imagen->nombre));
        }
        imagen::destroy($imagen->id);
      }
      try {
        // Eliminar los personajes relevantes existentes
        DB::table('personajes_relevantes')->where('relato', '=', $request->id_borrar)->delete();
      } catch (\Illuminate\Database\QueryException $excepcion) {
      } catch (Exception $excepcion) {
      }

      Articulo::destroy($request->id_borrar);

      return redirect()->route('relatos')->with('message', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('ArticuloController->destroy_relato: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('relatos')->with('error', 'Se produjo un problema en la base de datos, no se pudo borrar.' . $excepcion->getMessage());
    } catch (Exception $excepcion) {
      Log::error('ArticuloController->destroy_relato: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('relatos')->with('error', $excepcion->getMessage());
    }
  }
}
