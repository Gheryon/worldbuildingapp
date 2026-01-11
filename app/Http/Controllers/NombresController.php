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
    $hombres = Nombres::get_nombres_hombres();

    $mujeres = Nombres::get_nombres_mujeres();

    $lugares = Nombres::get_nombres_lugares();

    $sindecidir = Nombres::get_nombres_indeterminados();

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
      'nuevo_nombre' => 'required|max:128',
    ]);

    $id = $request->input('id');
    $nuevo = $request->input('nuevo_nombre');

    $exito = Nombres::store_nombre($nuevo, $id);

    if ($exito) {
      return redirect()->back()->with('message', $nuevo . ' añadido correctamente.');
    } else {
      return redirect()->back()->with('error', 'No se pudo añadir el nombre. Revisa los datos.');
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
      'nombres_editar' => 'required',
    ]);

    $id = $request->input('id_editar');
      $nuevo = $request->input('nombres_editar');
    $exito = Nombres::update_nombres($nuevo, $id);

    if ($exito) {
      return redirect()->back()->with('message', 'Editado correctamente.');
    } else {
      return redirect()->back()->with('error', 'No se pudo actualizar la lista. Revisa los datos.');
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
