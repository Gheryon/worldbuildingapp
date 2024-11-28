<?php

namespace App\Http\Controllers;

use App\Models\Nombres;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NombresController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try {
      $hombres=DB::table('nombres')->select('lista')->where('tipo', '=', 'Hombres')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $hombres=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $hombres=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $mujeres=DB::table('nombres')->select('lista')->where('tipo', '=', 'Mujeres')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $mujeres=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $mujeres=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $lugares=DB::table('nombres')->select('lista')->where('tipo', '=', 'Lugares')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $lugares=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $lugares=['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $sindecidir=DB::table('nombres')->select('lista')->where('tipo', '=', 'Sin_decidir')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $sindecidir=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $sindecidir=['error' => ['error' => $excepcion->getMessage()]];
    }

    return view('nombres.index', ['hombres' => $hombres, 'mujeres' => $mujeres, 'lugares' => $lugares, 'sindecidir' => $sindecidir]);
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
  public function store_nombre(Request $request)
  {
    $request->validate([
      'nuevo_nombre'=>'required|max:128',
    ]);

    try{
      $id=$request->input('id');
      $hombres=Nombres::where('tipo', $id)->firstorfail();
      $nuevo=$request->input('nuevo_nombre');
      $nombres=$hombres->lista;
      $json=array();
      //los nombres se almacenan separados por una ',', con explode se convierten en un array
      $json=explode(', ', $nombres);
      //se añade el nombre nuevo y se reordena la lista de nombres
      array_push($json, $nuevo);
      sort($json);
      //los nombres se combinan en un array de nuevo y se guardan los cambios
      $nombres=implode(", ", $json);
      $hombres->lista=$nombres;
      $hombres->save();
  
      return redirect()->route('nombres.index')->with('message', $nuevo.' añadido correctamente.');      
    }catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('nombres.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('nombres.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Nombres $nombres)
  {
      //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Nombres $nombres)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Nombres $nombres)
  {
    $request->validate([
      'nombres_editar'=>'required',
    ]);

    try{
      $id=$request->input('id_editar');
      $nuevo=$request->input('nombres_editar');
      $nombres_old=Nombres::where('tipo', $id)->firstorfail();

      $nombres=$nombres_old->lista;
      $json=array();
      //los nombres se almacenan separados por una ',', con explode se convierten en un array y se reordenan
      $json=explode(', ', $nuevo);
      sort($json);
      //los nombres se combinan en un array de nuevo y se guardan los cambios
      $nombres=implode(", ", $json);
      $nombres_old->lista=$nombres;
      $nombres_old->save();
  
      return redirect()->route('nombres.index')->with('message', 'Editado correctamente.');      
    }catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('nombres.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('nombres.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Nombres $nombres)
  {
      //
  }
}
