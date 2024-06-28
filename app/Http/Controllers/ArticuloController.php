<?php

namespace App\Http\Controllers;

use App\Models\articulo;
use Articulo as GlobalArticulo;
use Illuminate\Http\Request;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $articulos = articulo::all();
      $articulos_order=$articulos->sortBy(['nombre', ['asc']]);
      return view('articulos.index', ['articulos' => $articulos_order]);
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
      $request->validate([
        'nombre'=>'required|max:128',
        'tipo'=>'required',
        'contenido'=>'required'
      ]);
  
      $articulo=new articulo();
      $articulo->nombre=$request->nombre;
      $articulo->tipo=$request->tipo;
      $articulo->contenido=$request->contenido;
  
      $articulo->save();
  
      return redirect()->route('articulos');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
      //$articulo=articulo::findorfail($id);
      return view('articulos.show', ['id'=>$id]);
    }

    /**
     * Get the specified resource.
     */
    public function get($id)
    {
      $articulo=articulo::findorfail($id);
      return response()->json([
        'articulo'=>$articulo,
      ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
      //$articulo=articulo::findorfail($id);
      return view('articulos.edit', ['id'=>$id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
      $request->validate([
        'nombre'=>'required',
        'contenido'=>'required',
        'tipo'=>'required'
      ]);
  
      $articulo=Articulo::find($id);
      $articulo->nombre=$request->nombre;
      $articulo->tipo=$request->tipo;
      $articulo->contenido=$request->contenido;
      $articulo->save();
  
      return redirect()->route('articulos')
      ->with('mensaje', 'articulo actualizado correctamente.')
      ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      /*
    return redirect()->route('articulos')
    ->with('mensaje', 'Usuario eliminado correctamente.')
    ->with('icono', 'success');
     */
      Articulo::destroy($id);
      return response()->json([
        'mensaje'=>"borrado",
      ]);
    }
}
