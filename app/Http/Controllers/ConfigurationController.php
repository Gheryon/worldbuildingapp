<?php

namespace App\Http\Controllers;

use App\Models\Nombres;
use App\Models\configuration;
use App\Models\tipo_asentamiento;
use App\Models\tipo_conflicto;
use App\Models\tipo_construccion;
use App\Models\tipo_lugar;
use App\Models\tipo_organizacion;
use App\Models\lineas_temporales;
use App\Models\Fecha;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class ConfigurationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try{//el nombre del mundo se guarda en la fila con el tipo Nombre_mundo de la tabla nombres
      $nombre_mundo=DB::table('nombres')->select('lista')->where('tipo', '=', 'Nombre_mundo')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $nombre_mundo=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $nombre_mundo=['error' => ['error' => $excepcion->getMessage()]];
    }

    try{//la fecha actual del mundo se guarda en la fila con el id 1
      $fecha=Fecha::where('id', '=', '1')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $fecha=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $fecha=['error' => ['error' => $excepcion->getMessage()]];
    }

    try{
      $tipos_asentamiento=tipo_asentamiento::orderBy('nombre', 'asc')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $tipos_asentamiento=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipos_asentamiento=['error' => ['error' => $excepcion->getMessage()]];
    }

    try{
      $tipos_conflicto=tipo_conflicto::orderBy('nombre', 'asc')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $tipos_conflicto=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipos_conflicto=['error' => ['error' => $excepcion->getMessage()]];
    }

    try{
      $tipos_construccion=tipo_construccion::orderBy('nombre', 'asc')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $tipos_construccion=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipos_construccion=['error' => ['error' => $excepcion->getMessage()]];
    }

    try{
      $tipos_lugar=tipo_lugar::orderBy('nombre', 'asc')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $tipos_lugar=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipos_lugar=['error' => ['error' => $excepcion->getMessage()]];
    }

    try{
      $tipos_organizacion=tipo_organizacion::orderBy('nombre', 'asc')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $tipos_organizacion=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipos_organizacion=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $lineas_temporales=lineas_temporales::orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $lineas_temporales=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $lineas_temporales=['error' => ['error' => $excepcion->getMessage()]];
    }
    return view('config.index', ['tipos_asentamiento'=>$tipos_asentamiento, 'tipos_conflicto'=>$tipos_conflicto, 'tipos_construccion'=>$tipos_construccion, 'tipos_lugar'=>$tipos_lugar, 'tipos_organizaciones'=>$tipos_organizacion, 'lineas_temporales'=>$lineas_temporales, 'Nombre_mundo'=>$nombre_mundo[0]->lista, 'fecha'=>$fecha[0]]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store_tipo_asentamiento(Request $request)
  {
    $request->validate([
      'nuevo_tipo_asentamiento'=>'required|max:128',
    ]);

    try{
      $nuevo=new tipo_asentamiento();
      $nuevo->nombre=$request->input('nuevo_tipo_asentamiento');
      $nuevo->save();
  
      return redirect()->route('config.index')->with('message', $nuevo->nombre.' añadido correctamente.');      
    }catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  public function store_tipo_conflicto(Request $request)
  {
    $request->validate([
      'nuevo_tipo_conflicto'=>'required|max:128',
    ]);

    $nuevo=new tipo_conflicto();
    $nuevo->nombre=$request->input('nuevo_tipo_conflicto');
    $nuevo->save();

    return redirect()->route('config.index')->with('message', $nuevo->nombre.' añadido correctamente.');
  }

  public function store_tipo_construccion(Request $request)
  {
    $request->validate([
      'nuevo_tipo_construccion'=>'required|max:128',
    ]);

    $nuevo=new tipo_construccion();
    $nuevo->nombre=$request->input('nuevo_tipo_construccion');
    $nuevo->save();

    return redirect()->route('config.index')->with('message', $nuevo->nombre.' añadido correctamente.');
  }

  public function store_tipo_lugar(Request $request)
  {
    $request->validate([
      'nuevo_tipo_lugar'=>'required|max:128',
    ]);

    $nuevo=new tipo_lugar();
    $nuevo->nombre=$request->input('nuevo_tipo_lugar');
    $nuevo->save();

    return redirect()->route('config.index')->with('message', $nuevo->nombre.' añadido correctamente.');
  }

  public function store_tipo_organizacion(Request $request)
  {
    $request->validate([
      'nuevo_tipo_organizacion'=>'required|max:128',
    ]);

    try {
      $nuevo=new tipo_organizacion();
      $nuevo->nombre=$request->input('nuevo_tipo_organizacion');
      $nuevo->save();
      return redirect()->route('config.index')->with('message', $nuevo->nombre.' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  public function store_linea_temporal(Request $request)
  {
    $request->validate([
      'nueva_linea_temporal'=>'required|max:128',
    ]);

    try {
      $nuevo=new lineas_temporales();
      $nuevo->nombre=$request->input('nueva_linea_temporal');
      $nuevo->save();
      return redirect()->route('config.index')->with('message', $nuevo->nombre.' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(configuration $configuration)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(configuration $configuration)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_nombre_mundo(Request $request)
  {
    $request->validate([
      'nuevo_nombre_mundo'=>'required',
    ]);

    try{
      $id=$request->input('id');
      $nuevo=$request->input('nuevo_nombre_mundo');
      $nombre_old=Nombres::where('tipo', $id)->firstorfail();

      $nombre_old->lista=$nuevo;
      $nombre_old->save();
  
      return redirect()->route('config.index')->with('message', 'Nombre del mundo editado correctamente.');      
    }catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_fecha_mundo(Request $request)
  {
    $request->validate([
      'dia'=>'required|integer|min:1|max:30',
      'mes'=>'required',
      'anno'=>'required|integer',
    ]);

    try{
      //se le manda 1 al final que es el id de la fila en el que se guarda la fecha del mundo
      app(ConfigurationController::class)->update_fecha($request->input('dia', 0), $request->input('mes', 0), $request->input('anno', 0), 1);
  
      return redirect()->route('config.index')->with('message', 'Fecha del mundo editada correctamente.');      
    }catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    try {
      if($request->tipo_editar=='asentamiento'){
        $tipo_editar=tipo_asentamiento::findorfail($request->id_editar);
      }
      if($request->tipo_editar=='conflicto'){
        $tipo_editar=tipo_conflicto::findorfail($request->id_editar);
      }
      if($request->tipo_editar=='construccion'){
        $tipo_editar=tipo_construccion::findorfail($request->id_editar);
      }
      if($request->tipo_editar=='lugar'){
        $tipo_editar=tipo_lugar::findorfail($request->id_editar);
      }
      if($request->tipo_editar=='organizacion'){
        $tipo_editar=tipo_organizacion::findorfail($request->id_editar);
      }
      if($request->tipo_editar=='linea_temporal'){
        $tipo_editar=lineas_temporales::findorfail($request->id_editar);
      }
      $tipo_editar->nombre=$request->nombre_editar;
      $tipo_editar->save();
      return redirect()->route('config.index')->with('message', $tipo_editar->nombre.' editado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(request $request)
  {
    try{
      if($request->tipo=='asentamiento'){
        tipo_asentamiento::destroy($request->id_borrar);
      }
      if($request->tipo=='conflicto'){
        tipo_conflicto::destroy($request->id_borrar);
      }
      if($request->tipo=='construccion'){
        tipo_construccion::destroy($request->id_borrar);
      }
      if($request->tipo=='lugar'){
        tipo_lugar::destroy($request->id_borrar);
      }
      if($request->tipo=='organizacion'){
        tipo_organizacion::destroy($request->id_borrar);
      }
      if($request->tipo=='linea_temporal'){
        lineas_temporales::destroy($request->id_borrar);
      }
      return redirect()->route('config.index')->with('message', $request->nombre_borrado.' borrado correctamente.');
      }catch (\Illuminate\Database\QueryException $excepcion) {
        return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
      } catch (Exception $excepcion) {
        return redirect()->route('config.index')->with('error', $excepcion->getMessage());
      }
    return redirect()->route('config.index')->with('error', 'No se pudo borrar.');
  }

  /**
   * Store the specified resource in storage.
   */
  public function store_fecha($dia=0, $mes=0, $anno=0, $tabla)
  {
    try {
      //si los input de las fechas no se introducen, la fecha es indeterminada, no se guarda fecha
      if($anno==0&&$mes==0&&$dia==0){
        $id_fecha=0;
      }else{
        $fecha=new Fecha();
        $fecha->tabla=$tabla;
        $fecha->anno=$anno;
        $fecha->mes=$mes;
        $fecha->dia=$dia;
        $fecha->save();
        $id_fecha=DB::scalar("SELECT MAX(id) as id FROM fechas");
      }
      return $id_fecha;
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_fecha($dia=0, $mes=0, $anno=0, $id)
  {
    try {
      $fecha=Fecha::findorfail($id);
      $fecha->anno=$anno;
      $fecha->mes=$mes;
      $fecha->dia=$dia;
      $fecha->save();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }
}
