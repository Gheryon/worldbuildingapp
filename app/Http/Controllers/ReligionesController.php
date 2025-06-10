<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use App\Models\Fecha;
use App\Models\imagen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReligionesController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index($orden = 'asc')
  {
    try {
      $religiones = DB::table('religiones')
        ->select('id', 'nombre', 'descripcion')
        ->orderBy('nombre', $orden)->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $religiones = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $religiones = ['error' => ['error' => $excepcion->getMessage()]];
    }

    return view('religiones.index', ['religiones' => $religiones, 'orden' => $orden]);
  }

  /**
   * Show the form for creating a new resource.
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
      'nombre' => 'required|max:256',
      'lema' => 'nullable|max:256',
      'dfundacion' => 'nullable|integer|min:1|max:30',
      'afundacion' => 'nullable|integer',
      'ddisolucion' => 'nullable|integer|min:1|max:30',
      'adisolucion' => 'nullable|integer',
      'escudo' => 'file|image|mimes:jpg,png,gif|max:10240',
    ]);

    try {
      $religion = new Religion();

      $religion->nombre = $request->nombre;
      $religion->save();

      $id_religion = DB::scalar("SELECT MAX(id) as id FROM religiones");
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('religiones.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo añadir.');
    } catch (Exception $excepcion) {
      return redirect()->route('religiones.index')->with('error', $excepcion->getMessage());
    }

    if ($request->filled('lema')) {
      $religion->lema = $request->lema;
    }
    if ($request->filled('descripcion')) {
      $religion->descripcion = app(ImagenController::class)->store_for_summernote($request->descripcion, "religiones", $id_religion);
    }
    if ($request->filled('historia')) {
      $religion->Historia = app(ImagenController::class)->store_for_summernote($request->historia, "religiones", $id_religion);
    }
    if ($request->filled('cosmologia')) {
      $religion->cosmologia = app(ImagenController::class)->store_for_summernote($request->cosmologia, "religiones", $id_religion);
    }
    if ($request->filled('doctrina')) {
      $religion->doctrina = app(ImagenController::class)->store_for_summernote($request->doctrina, "religiones", $id_religion);
    }
    if ($request->filled('sagrado')) {
      $religion->sagrado = app(ImagenController::class)->store_for_summernote($request->sagrado, "religiones", $id_religion);
    }
    if ($request->filled('fiestas')) {
      $religion->fiestas = app(ImagenController::class)->store_for_summernote($request->fiestas, "religiones", $id_religion);
    }
    if ($request->filled('politica')) {
      $religion->politica = app(ImagenController::class)->store_for_summernote($request->politica, "religiones", $id_religion);
    }
    if ($request->filled('estructura')) {
      $religion->estructura = app(ImagenController::class)->store_for_summernote($request->estructura, "religiones", $id_religion);
    }
    if ($request->filled('sectas')) {
      $religion->sectas = app(ImagenController::class)->store_for_summernote($request->sectas, "religiones", $id_religion);
    }
    if ($request->filled('otros')) {
      $religion->otros = app(ImagenController::class)->store_for_summernote($request->otros, "religiones", $id_religion);
    }

    try {
      //------------escudo----------//
      if ($request->hasFile('escudo')) {
        $path = $request->file('escudo')->store('escudos', 'public');
        $religion->escudo = basename($path);
      } else {
        $religion->escudo = "default.png";
      }

      //------------fechas----------//
      $religion->fundacion = app(ConfigurationController::class)->store_fecha($request->input('dfundacion', 0), $request->input('mfundacion', 0), $request->input('afundacion', 0), "religiones");
      $religion->disolucion = app(ConfigurationController::class)->store_fecha($request->input('ddisolucion', 0), $request->input('mdisolucion', 0), $request->input('adisolucion', 0), "religiones");

      $religion->save();
      return redirect()->route('religiones.index')->with('message', 'Religión añadida correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('religiones.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo añadir.');
    } catch (Exception $excepcion) {
      return redirect()->route('religiones.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Religion $religion)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $religion = Religion::findorfail($id);
    $fecha_disolucion = 0;
    $fecha_fundacion = 0;
    if ($religion->fundacion != 0) {
      $fecha_fundacion = Fecha::find($religion->fundacion);
    } else {
      $fecha_fundacion = Fecha::find(0);
    }

    if ($religion->disolucion != 0) {
      $fecha_disolucion = Fecha::find($religion->disolucion);
    } else {
      $fecha_disolucion = Fecha::find(0);
    }

    return view('religiones.edit', ['religion' => $religion, 'fundacion' => $fecha_fundacion, 'disolucion' => $fecha_disolucion]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'lema' => 'nullable|max:256',
      'dfundacion' => 'nullable|integer|min:1|max:30',
      'afundacion' => 'nullable|integer',
      'ddisolucion' => 'nullable|integer|min:1|max:30',
      'adisolucion' => 'nullable|integer',
      'escudo' => 'file|image|mimes:jpg,png,gif|max:10240',
    ]);

    try {
      $religion = Religion::findorfail($request->id);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('religiones.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo editar.');
    } catch (Exception $excepcion) {
      return redirect()->route('religiones.index')->with('error', $excepcion->getMessage());
    }

    if ($request->filled('nombre')) {
      $religion->nombre = $request->nombre;
    }
    if ($request->filled('lema')) {
      $religion->lema = $request->lema;
    }
    if ($request->filled('descripcion')) {
      $religion->descripcion = app(ImagenController::class)->update_for_summernote($request->descripcion, "religiones", $request->id);
    }
    if ($request->filled('historia')) {
      $religion->historia = app(ImagenController::class)->update_for_summernote($request->historia, "religiones", $request->id);
    }
    if ($request->filled('cosmologia')) {
      $religion->cosmologia = app(ImagenController::class)->update_for_summernote($request->cosmologia, "religiones", $request->id);
    }
    if ($request->filled('doctrina')) {
      $religion->doctrina = app(ImagenController::class)->update_for_summernote($request->doctrina, "religiones", $request->id);
    }
    if ($request->filled('sagrado')) {
      $religion->sagrado = app(ImagenController::class)->update_for_summernote($request->sagrado, "religiones", $request->id);
    }
    if ($request->filled('fiestas')) {
      $religion->fiestas = app(ImagenController::class)->update_for_summernote($request->fiestas, "religiones", $request->id);
    }
    if ($request->filled('politica')) {
      $religion->politica = app(ImagenController::class)->update_for_summernote($request->politica, "religiones", $request->id);
    }
    if ($request->filled('estructura')) {
      $religion->estructura = app(ImagenController::class)->update_for_summernote($request->estructura, "religiones", $request->id);
    }
    if ($request->filled('sectas')) {
      $religion->sectas = app(ImagenController::class)->update_for_summernote($request->sectas, "religiones", $request->id);
    }
    if ($request->filled('otros')) {
      $religion->otros = app(ImagenController::class)->update_for_summernote($request->otros, "religiones", $request->id);
    }

    //------------fechas----------//
    $religion->fundacion = $request->input('id_fundacion', 0);
    $religion->disolucion = $request->input('id_disolucion', 0);

    try {
      //------------escudo----------//
      if ($request->hasFile('escudo')) {
        //el escudo anterior hay que borrarlo salvo que sea default.png
        if ($religion->escudo != "default.png") {
          if (file_exists('storage/escudos/' . $religion->escudo)) {
            unlink('storage/escudos/' . $religion->escudo);
          }
        }
        $path = $request->file('escudo')->store('escudos', 'public');
        $religion->escudo = basename($path);
      }

      if ($request->input('dfundacion', 0) != 0) {
        if ($religion->fundacion != 0) {
          //la religion ya tenía fecha de fundacion antes de editar, se actualiza
          app(ConfigurationController::class)->update_fecha($request->input('dfundacion', 0), $request->input('mfundacion', 0), $request->input('afundacion', 0), $religion->fundacion);
        } else {
          //la religion no tenía fecha de fundacion antes de editar, hay que añadirla a la db.
          $religion->fundacion = app(ConfigurationController::class)->store_fecha($request->input('dfundacion', 0), $request->input('mfundacion', 0), $request->input('afundacion', 0), "religiones");
        }
      }

      if ($request->input('ddisolucion', 0) != 0) {
        if ($religion->disolucion != 0) {
          //la religion ya tenía fecha de disolucion antes de editar
          app(ConfigurationController::class)->update_fecha($request->input('ddisolucion', 0), $request->input('mdisolucion', 0), $request->input('adisolucion', 0), $religion->disolucion);
        } else {
          //la religion no tenía fecha de disolucion antes de editar, hay que añadirla a la db.
          $religion->disolucion = app(ConfigurationController::class)->store_fecha($request->input('ddisolucion', 0), $request->input('mdisolucion', 0), $request->input('adisolucion', 0), "religiones");
        }
      }

      $religion->save();
      return redirect()->route('religiones.index')->with('message', 'Religión editada correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('religiones.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo editar.');
      //echo $excepcion;
    } catch (Exception $excepcion) {
      return redirect()->route('religiones.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    try {
      $fundacion = DB::scalar("SELECT fundacion FROM religiones where id = ?", [$request->id_borrar]);
      $disolucion = DB::scalar("SELECT disolucion FROM religiones where id = ?", [$request->id_borrar]);
      $escudo = DB::scalar("SELECT escudo FROM religiones where id = ?", [$request->id_borrar]);

      if ($escudo != "default.png") {
        if (file_exists('storage/escudos/' . $escudo)) {
          unlink('storage/escudos/' . $escudo);
        }
      }

      //borrado de las imagenes que pueda haber de summernote
      $imagenes = DB::table('imagenes')
        ->select('id', 'nombre')
        ->where('table_owner', '=', 'religiones')
        ->where('owner', '=', $request->id_borrar)->get();

      foreach ($imagenes as $imagen) {
        if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
          unlink(public_path("/storage/imagenes/" . $imagen->nombre));
          //Storage::delete(asset($imagen->nombre));
        }
        imagen::destroy($imagen->id);
      }
      Religion::destroy($request->id_borrar);

      //si fundacion/disolucion != 0, la religion tiene fechas establecidas, hay que borrarlas
      if ($fundacion != 0) {
        Fecha::destroy($fundacion);
      }
      if ($disolucion != 0) {
        Fecha::destroy($disolucion);
      }
      return redirect()->route('religiones.index')->with('message', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('religiones.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo borrar.');
    } catch (Exception $excepcion) {
      return redirect()->route('religiones.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display a listing of the resource searched.
   */
  public function search(Request $request)
  {
    $search = $request->input('search');
    try {
      $religiones = DB::table('religiones')
        ->select('id', 'nombre', 'descripcion')
        ->where('nombre', 'LIKE', "%{$search}%")
        ->orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $religiones = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $religiones = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return view('religiones.index', ['religiones' => $religiones, 'orden' => 'asc']);
  }

  /**
   * Display a listing of the resource searched.
   */
  public function getReligiones(Request $request)
  {
    $query = $request->input('q');
    $religiones = Religion::where('nombre', 'LIKE', "%{$query}%")
      ->orderBy('nombre', 'asc')
      ->get(['id', 'nombre']);

    return response()->json($religiones);
  }
}
