<?php

namespace App\Http\Controllers;

use App\Models\timeline;
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
    try{
      $eventos=timeline::orderBy('anno', $orden)->get();
      if($cronologia!=0){
        $eventos=$eventos->whereIn('id_linea_temporal', [1, $cronologia]);
//                          ->orWhere('id_linea_temporal', '1');
      }
      $timelines=DB::select('select id, nombre from lineas_temporales');
      return view('timelines.index', ['eventos' => $eventos, 'timelines'=>$timelines, 'orden'=>$orden, 'cronologia'=>$cronologia]);

    }catch(\Illuminate\Database\QueryException $excepcion){
      return view('timelines.index')->with('error', 'Se produjo un problema en la base de datos.');
    }catch(Exception $excepcion){
      $timelines=DB::select('select id, nombre from lineas_temporales');
      return view('timelines.index', ['timelines'=>$timelines, 'orden'=>$orden, 'cronologia'=>$cronologia])->with('error', $excepcion->getMessage());
    }
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
      //'dia'=>'integer',
      //'mes'=>'integer',
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
      $evento->id_tipo_evento=1;

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
