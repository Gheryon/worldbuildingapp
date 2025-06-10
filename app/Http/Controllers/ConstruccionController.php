<?php

namespace App\Http\Controllers;

use App\Models\imagen;
use App\Models\Construccion;
use App\Models\Fecha;
use App\Http\Controllers\ImagenController;
use App\Models\tipo_construccion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConstruccionController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index($orden = 'asc', $tipo = '0')
  {
    try {
      if ($tipo != 0) {
        $construcciones = DB::table('construccions')
          ->leftjoin('tipo_construccion', 'construccions.tipo', '=', 'tipo_construccion.id')
          ->select('construccions.id', 'construccions.nombre', 'descripcion', 'tipo_construccion.nombre AS tipo')
          ->where('construccions.tipo', '=', $tipo)
          ->orderBy('construccions.nombre', $orden)->get();
      } else {
        $construcciones = DB::table('construccions')
          ->leftjoin('tipo_construccion', 'construccions.tipo', '=', 'tipo_construccion.id')
          ->select('construccions.id', 'construccions.nombre', 'descripcion', 'tipo_construccion.nombre AS tipo')
          ->orderBy('construccions.nombre', $orden)->get();
      }
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $construcciones = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $construcciones = ['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $tipos = tipo_construccion::orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $tipos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipos = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return view('construcciones.index', ['construcciones' => $construcciones, 'tipos' => $tipos, 'orden' => $orden, 'tipo_o' => $tipo]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    try {
      $tipo_construccion = tipo_construccion::orderBy('nombre', 'asc')->get();

      $ubicaciones = DB::table('asentamientos')->select('id', 'nombre')
        ->where('id', '!=', 0)->orderBy('nombre', 'asc')->get();

      return view('construcciones.create', ['tipos' => $tipo_construccion, 'ubicaciones' => $ubicaciones]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('construcciones.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo añadir.');
    } catch (Exception $excepcion) {
      return redirect()->route('construcciones.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'select_tipo' => 'required',
      'dconstruccion' => 'nullable|integer|min:1|max:30',
      'aconstruccion' => 'nullable|integer',
      'ddestruccion' => 'nullable|integer|min:1|max:30',
      'adestruccion' => 'nullable|integer',
    ]);

    try {
      $construccion = new Construccion();
      $construccion->save();

      $id_construccion = DB::scalar("SELECT MAX(id) as id FROM construccions");
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('construcciones.index')->with('error', 'Se produjo un problema en la base de datos.' . $excepcion->getMessage());
    } catch (Exception $excepcion) {
      return redirect()->route('construcciones.index')->with('error', $excepcion->getMessage());
    }

    if ($request->filled('nombre')) {
      $construccion->nombre = $request->nombre;
    }
    if ($request->filled('descripcion')) {
      $construccion->descripcion = app(ImagenController::class)->update_for_summernote($request->descripcion, "construcciones", $id_construccion);
    }
    if ($request->filled('historia')) {
      $construccion->historia = app(ImagenController::class)->update_for_summernote($request->historia, "construcciones", $id_construccion);
    }
    if ($request->filled('proposito')) {
      $construccion->proposito = app(ImagenController::class)->update_for_summernote($request->proposito, "construcciones", $id_construccion);
    }
    if ($request->filled('aspecto')) {
      $construccion->aspecto = app(ImagenController::class)->update_for_summernote($request->aspecto, "construcciones", $id_construccion);
    }
    if ($request->filled('otros')) {
      $construccion->otros = app(ImagenController::class)->update_for_summernote($request->otros, "construcciones", $id_construccion);
    }

    $construccion->tipo = $request->select_tipo;
    $construccion->ubicacion = $request->select_ubicacion;

    try {
      $construccion->construccion = app(ConfigurationController::class)->store_fecha($request->input('dconstruccion', 0), $request->input('mconstruccion', 0), $request->input('aconstruccion', 0), "construcciones");
      $construccion->destruccion = app(ConfigurationController::class)->store_fecha($request->input('ddestruccion', 0), $request->input('mdestruccion', 0), $request->input('adestruccion', 0), "construcciones");

      $construccion->save();
      return redirect()->route('construcciones.index')->with('message', 'Construcción ' . $construccion->nombre . ' añadida correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('construcciones.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo añadir.' . $excepcion->getMessage());
    } catch (Exception $excepcion) {
      return redirect()->route('construcciones.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    try {
      $tipos = tipo_construccion::orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $tipos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipos = ['error' => ['error' => $excepcion->getMessage()]];
    }
    try {
      $ubicaciones = DB::table('asentamientos')->select('id', 'nombre')
        ->where('id', '!=', 0)->orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $tipos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipos = ['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $construccion = Construccion::findorfail($id);

      if ($construccion->construccion != 0) {
        $construccion_ini = Fecha::find($construccion->construccion);
      } else {
        $construccion_ini = Fecha::find(0);
      }

      if ($construccion->destruccion != 0) {
        $construccion_fin = Fecha::find($construccion->destruccion);
      } else {
        $construccion_fin = Fecha::find(0);
      }
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return view('construcciones.index')->with('error', 'Se produjo un problema en la base de datos.' . $excepcion->getMessage());
    } catch (Exception $excepcion) {
      return view('construcciones.index')->with('error', $excepcion->getMessage());
    }
    return view('construcciones.edit', ['construccion' => $construccion, 'construccion_ini' => $construccion_ini, 'construccion_fin' => $construccion_fin, 'tipos' => $tipos, 'ubicaciones' => $ubicaciones]);
  }
  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'select_tipo' => 'required',
      'dconstruccion' => 'nullable|integer|min:1|max:30',
      'aconstruccion' => 'nullable|integer',
      'ddestruccion' => 'nullable|integer|min:1|max:30',
      'adestruccion' => 'nullable|integer',
    ]);

    try {
      $construccion = Construccion::findorfail($request->id);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('construcciones.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo editar.' . $excepcion->getMessage());
    } catch (Exception $excepcion) {
      return redirect()->route('construcciones.index')->with('error', $excepcion->getMessage());
    }

    $construccion->nombre = $request->nombre;
    $construccion->ubicacion = $request->select_ubicacion;
    $construccion->tipo = $request->select_tipo;

    //inputs de summernote
    if ($request->filled('descripcion')) {
      $construccion->descripcion = app(ImagenController::class)->update_for_summernote($request->descripcion, "construcciones", $request->id);
    }
    if ($request->filled('historia')) {
      $construccion->historia = app(ImagenController::class)->update_for_summernote($request->historia, "construcciones", $request->id);;
    }
    if ($request->filled('proposito')) {
      $construccion->proposito = app(ImagenController::class)->update_for_summernote($request->proposito, "construcciones", $request->id);;
    }
    if ($request->filled('aspecto')) {
      $construccion->aspecto = app(ImagenController::class)->update_for_summernote($request->aspecto, "construcciones", $request->id);;
    }
    if ($request->filled('otros')) {
      $construccion->otros = app(ImagenController::class)->update_for_summernote($request->otros, "construcciones", $request->id);;
    }

    //------------fechas----------//
    $construccion->construccion = $request->input('id_construccion', 0);
    $construccion->destruccion = $request->input('id_destruccion', 0);

    try {

      if ($request->input('aconstruccion', 0) != 0) {
        if ($construccion->construccion != 0) {
          //la construccion ya tenía fecha de construccion antes de editar
          app(ConfigurationController::class)->update_fecha($request->input('dconstruccion', 0), $request->input('mconstruccion', 0), $request->input('aconstruccion', 0), $construccion->construccion);
        } else {
          //la construccion no tenía fecha de construccion antes de editar, hay que añadirla a la db.
          $construccion->construccion = app(ConfigurationController::class)->store_fecha($request->input('dconstruccion', 0), $request->input('mconstruccion', 0), $request->input('aconstruccion', 0), "construcciones");
        }
      }

      if ($request->input('adestruccion', 0) != 0) {
        if ($construccion->destruccion != 0) {
          //el construccion ya tenía fecha de destruccion antes de editar
          app(ConfigurationController::class)->update_fecha($request->input('ddestruccion', 0), $request->input('mdestruccion', 0), $request->input('adestruccion', 0), $construccion->destruccion);
        } else {
          //el construccion no tenía fecha de destruccion antes de editar, hay que añadirla a la db.
          $construccion->destruccion = app(ConfigurationController::class)->store_fecha($request->input('ddestruccion', 0), $request->input('mdestruccion', 0), $request->input('adestruccion', 0), "construcciones");
        }
      }

      $construccion->save();
      return redirect()->route('construcciones.index')->with('message', $construccion->nombre . ' editado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('construcciones.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo editar.' . $excepcion->getMessage());
    } catch (Exception $excepcion) {
      return redirect()->route('construcciones.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    try {
      $construccion = DB::scalar("SELECT construccion FROM construccions where id = ?", [$request->id_borrar]);
      $destruccion = DB::scalar("SELECT destruccion FROM construccions where id = ?", [$request->id_borrar]);

      //borrado de las imagenes que pueda haber de summernote
      $imagenes = DB::table('imagenes')
        ->select('id', 'nombre')
        ->where('table_owner', '=', 'construcciones')
        ->where('owner', '=', $request->id_borrar)->get();

      foreach ($imagenes as $imagen) {
        if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
          unlink(public_path("/storage/imagenes/" . $imagen->nombre));
          //Storage::delete(asset($imagen->nombre));
        }
        imagen::destroy($imagen->id);
      }
      Construccion::destroy($request->id_borrar);

      //si construccion/destruccion != 0, la construccion tiene fecha establecida, hay que borrar
      if ($construccion != 0) {
        Fecha::destroy($construccion);
      }
      if ($destruccion != 0) {
        Fecha::destroy($destruccion);
      }

      return redirect()->route('construcciones.index')->with('message', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('construcciones.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo borrar.');
    } catch (Exception $excepcion) {
      return redirect()->route('construcciones.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display a listing of the resource searched.
   */
  public function search(Request $request)
  {
    $search = $request->input('search');
    try {
      $construcciones = DB::table('construccions')
        ->join('tipo_construccion', 'construccions.tipo', '=', 'tipo_construccion.id')
        ->select('construccions.id', 'construccions.nombre', 'construccions.descripcion', 'tipo_construccion.nombre AS tipo')
        ->where('construccions.nombre', 'LIKE', "%{$search}%")
        ->orderBy('construccions.nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $construcciones = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $construcciones = ['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $tipos = tipo_construccion::orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $tipos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipos = ['error' => ['error' => $excepcion->getMessage()]];
    }
    
    return view('construcciones.index', ['construcciones' => $construcciones, 'tipos'=>$tipos, 'orden'=>'asc', 'tipo_o'=>0]);
  }
}
