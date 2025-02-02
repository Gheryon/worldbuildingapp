<?php

namespace App\Http\Controllers;

use App\Models\timeline;
use App\Models\lineas_temporales;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class TimelineController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index($orden='desc', $cronologia='0')
  {
    try {
      $nacimientos=DB::table('personaje')
          ->select('personaje.id', 'fechas.dia', 'fechas.mes', 'fechas.anno')
          ->selectRaw("CONCAT(' ', ' ') AS descripcion")
          ->selectRaw(" personaje.Nombre AS nombre, CONCAT('nace_personaje') AS tipo")
          ->where('personaje.id', '!=', 0)
          ->where('personaje.nacimiento', '!=', 0)
          ->leftjoin('fechas', 'personaje.nacimiento', '=', 'fechas.id');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $nacimientos=['error' => ['error' => 'Se produjo un problema en la base de datos.'.$excepcion->getMessage()]];
    } catch (Exception $excepcion) {
      $nacimientos=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $defunciones=DB::table('personaje')
          ->select('personaje.id', 'fechas.dia', 'fechas.mes', 'fechas.anno')
          ->selectRaw(" CONCAT(' ', ' ') AS descripcion")
          ->selectRaw("personaje.Nombre AS nombre, CONCAT('muere_personaje') AS tipo")
          ->where('personaje.id', '!=', 0)
          ->where('personaje.fallecimiento', '!=', 0)
          ->leftjoin('fechas', 'personaje.fallecimiento', '=', 'fechas.id');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $defunciones=['error' => ['error' => 'Se produjo un problema en la base de datos.'.$excepcion->getMessage()]];
    } catch (Exception $excepcion) {
      $defunciones=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $conflictos_ini=DB::table('conflicto')
          ->select('conflicto.id', 'fechas.dia', 'fechas.mes', 'fechas.anno', 'conflicto.descripcion')
          ->selectRaw("conflicto.nombre AS nombre, CONCAT('ini_conflicto') AS tipo")
          ->where('conflicto.id', '!=', 0)
          ->where('conflicto.fecha_inicio', '!=', 0)
          ->leftjoin('fechas', 'conflicto.fecha_inicio', '=', 'fechas.id');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $conflictos_ini=['error' => ['error' => 'Se produjo un problema en la base de datos.'.$excepcion->getMessage()]];
    } catch (Exception $excepcion) {
      $conflictos_ini=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $conflictos_fin=DB::table('conflicto')
          ->select('conflicto.id', 'fechas.dia', 'fechas.mes', 'fechas.anno', 'conflicto.descripcion')
          ->selectRaw("conflicto.nombre AS nombre, CONCAT('fin_conflicto') AS tipo")
          ->where('conflicto.id', '!=', 0)
          ->where('conflicto.fecha_fin', '!=', 0)
          ->leftjoin('fechas', 'conflicto.fecha_fin', '=', 'fechas.id');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $conflictos_fin=['error' => ['error' => 'Se produjo un problema en la base de datos.'.$excepcion->getMessage()]];
    } catch (Exception $excepcion) {
      $conflictos_fin=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $asentamientos_ini=DB::table('asentamientos')
          ->select('asentamientos.id', 'fechas.dia', 'fechas.mes', 'fechas.anno')
          ->selectRaw(" CONCAT(' ', ' ') AS descripcion")
          ->selectRaw("asentamientos.nombre AS nombre, CONCAT('ini_asentamiento') AS tipo")
          ->where('asentamientos.id', '!=', 0)
          ->where('asentamientos.fundacion', '!=', 0)
          ->leftjoin('fechas', 'asentamientos.fundacion', '=', 'fechas.id');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $asentamientos_ini=['error' => ['error' => 'Se produjo un problema en la base de datos.'.$excepcion->getMessage()]];
    } catch (Exception $excepcion) {
      $asentamientos_ini=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $asentamientos_fin=DB::table('asentamientos')
      ->select('asentamientos.id', 'fechas.dia', 'fechas.mes', 'fechas.anno')
      ->selectRaw(" CONCAT(' ', ' ') AS descripcion")
      ->selectRaw("asentamientos.nombre AS nombre, CONCAT('fin_asentamiento') AS tipo")
      ->where('asentamientos.id', '!=', 0)
      ->where('asentamientos.disolucion', '!=', 0)
      ->leftjoin('fechas', 'asentamientos.disolucion', '=', 'fechas.id');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $asentamientos_fin=['error' => ['error' => 'Se produjo un problema en la base de datos.'.$excepcion->getMessage()]];
    } catch (Exception $excepcion) {
      $asentamientos_fin=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $organizaciones_ini=DB::table('organizaciones')
      ->select('organizaciones.id_organizacion AS id', 'fechas.dia', 'fechas.mes', 'fechas.anno')
      ->selectRaw(" CONCAT(' ', ' ') AS descripcion")
      ->selectRaw("organizaciones.nombre AS nombre, CONCAT('ini_organizacion') AS tipo")
      ->where('organizaciones.id_organizacion', '!=', 0)
      ->where('organizaciones.fundacion', '!=', 0)
      ->leftjoin('fechas', 'organizaciones.fundacion', '=', 'fechas.id');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $organizaciones_ini=['error' => ['error' => 'Se produjo un problema en la base de datos.'.$excepcion->getMessage()]];
    } catch (Exception $excepcion) {
      $organizaciones_ini=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $organizaciones_fin=DB::table('organizaciones')
      ->select('organizaciones.id_organizacion AS id', 'fechas.dia', 'fechas.mes', 'fechas.anno')
      ->selectRaw(" CONCAT(' ', ' ') AS descripcion")
      ->selectRaw("organizaciones.nombre AS nombre, CONCAT('fin_organizacion') AS tipo")
      ->where('organizaciones.id_organizacion', '!=', 0)
      ->where('organizaciones.disolucion', '!=', 0)
      ->leftjoin('fechas', 'organizaciones.disolucion', '=', 'fechas.id');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $organizaciones_fin=['error' => ['error' => 'Se produjo un problema en la base de datos.'.$excepcion->getMessage()]];
    } catch (Exception $excepcion) {
      $organizaciones_fin=['error' => ['error' => $excepcion->getMessage()]];
    }
    
    try{
      $eventos=DB::table('timelines')
        ->select('id', 'dia', 'mes', 'anno', 'descripcion', 'nombre')
        ->selectRaw("CONCAT('BUTTONS') AS tipo")
        ->unionAll($nacimientos)
        ->unionAll($defunciones)
        ->unionAll($conflictos_ini)
        ->unionAll($conflictos_fin)
        ->unionAll($asentamientos_ini)
        ->unionAll($asentamientos_fin)
        ->unionAll($organizaciones_ini)
        ->unionAll($organizaciones_fin)
        ->orderBy('anno', $orden)
        ->get();
      if($cronologia!=0){
        //$eventos=$eventos->whereIn('id_linea_temporal', [1, $cronologia]);
//                          ->orWhere('id_linea_temporal', '1');
      }
    }catch(\Illuminate\Database\QueryException $excepcion){
      $eventos=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $eventos=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $lineas_temporales=lineas_temporales::orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $lineas_temporales=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $lineas_temporales=['error' => ['error' => $excepcion->getMessage()]];
    }

    return view('timelines.index', ['eventos' => $eventos, 'aux'=>$organizaciones_ini, 'timelines'=>$lineas_temporales, 'orden'=>$orden, 'cronologia'=>$cronologia]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //se hace desde modal en el index
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre'=>'required|max:128',
      'select_timeline'=>'integer',
      'dia'=>'nullable|numeric|integer',
      'mes'=>'nullable|numeric|integer',
      'anno'=>'required|integer',
      'descripcion'=>'required',
    ]);

    try{
      $id=$request->input('id_editar', 0);
      if($id!=0){
        $evento=timeline::find($id);
        $mensaje='Evento editado correctamente.';
      }else{
        $evento=new timeline();
        $mensaje='Evento añadido correctamente.';
      }
      $evento->nombre=$request->input('nombre');
      $evento->dia=$request->input('dia', 0);
      $evento->mes=$request->input('mes', 0);
      $evento->anno=$request->input('anno', 0);
      $evento->descripcion=$request->input('descripcion');
      $evento->id_linea_temporal=$request->input('select_timeline');

      $evento->save();
      return redirect()->route('timelines.index')->with('message',$mensaje);
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('timelines.index')->with('error', $excepcion->getMessage());
      //return redirect()->route('timelines.index')->with('error', 'Se produjo un problema en la base de datos.');
    }catch(Exception $excepcion){
      return redirect()->route('timelines.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(timeline $timeline)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $evento=timeline::find($id);
    return response()->json([
      'evento'=>$evento,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, timeline $timeline)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $timeline)
  {
    try{
      timeline::destroy($timeline->id_evento);
      return redirect()->route('timelines.index')->with('message','Evento borrado correctamente.');

    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('timelines.index')->with('error','Se produjo un problema en la base de datos, no se pudo borrar.');
    }catch(Exception $excepcion){
      return redirect()->route('timelines.index')->with('error',$excepcion->getMessage());
    }
  }
}
