<?php

namespace App\Http\Controllers;

use App\Models\Enlace;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class EnlacesController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try{
      $generadores=DB::table('enlaces')
        ->where('tipo', '=', 'generador')
        ->orderBy('nombre', 'asc')->get();
      $criaturas=DB::table('enlaces')
      ->where('tipo', '=', 'criatura')
      ->orderBy('nombre', 'asc')->get();
      $referencias=DB::table('enlaces')
      ->where('tipo', '=', 'referencia')
      ->orderBy('nombre', 'asc')->get();
      return view('enlaces.index', ['generadores' => $generadores, 'criaturas' => $criaturas, 'referencias' => $referencias]);

    }catch(\Illuminate\Database\QueryException $excepcion){
      return view('enlaces.index')->with('error', 'Se produjo un problema en la base de datos.');
    }catch(Exception $excepcion){
      return view('enlaces.index')->with('error', $excepcion->getMessage());
    }
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
  public function store(Request $request)
  {
    $request->validate([
      'nombre'=>'required|max:128',
      'url'=>'required|max:256',
      'tipo'=>'required',
    ]);

    try{
      $enlace=new Enlace();
  
      $enlace->nombre=$request->nombre;
      $enlace->url=$request->url;
      $enlace->tipo=$request->tipo;
  
      $enlace->save();
      return redirect()->route('enlaces.index')->with('message','Enlace añadido correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('enlaces.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('enlaces.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Enlace $enlace)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Enlace $enlace)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    $request->validate([
      'nombre_editar'=>'required|max:128',
      'url_editar'=>'required|max:256',
      'tipo_editar'=>'required',
    ]);

    try{
      $enlace=Enlace::find($request->id_editar);;
  
      $enlace->nombre=$request->nombre_editar;
      $enlace->url=$request->url_editar;
      $enlace->tipo=$request->tipo_editar;
  
      $enlace->save();
      return redirect()->route('enlaces.index')->with('message','Enlace editado correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('enlaces.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('enlaces.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    try{
      Enlace::destroy($request->id_borrar);
      return redirect()->route('enlaces.index')->with('message','Enlace borrado correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('enlaces.index')->with('error','Se produjo un problema en la base de datos, no se pudo borrar.');
    }catch(Exception $excepcion){
      return redirect()->route('enlaces.index')->with('error',$excepcion->getMessage());
    }
  }
}
