<?php

namespace App\Http\Controllers;

use App\Models\articulo;
use App\Models\imagen;
use App\Http\Controllers\ImagenController;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticuloController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index($orden='asc', $filtro='all')
  {
    try {
      //si filtro==all se obtienen todos, si filtro
      if($filtro=='all'){
        $articulos = articulo::orderBy('nombre', $orden)->get();
      }else{
        $articulos = articulo::where('tipo', '=', $filtro)->orderBy('nombre', $orden)->get();
      }
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $articulos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $articulos = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return view('articulos.index', ['articulos' => $articulos, 'orden'=>$orden, 'filtro_o'=>$filtro]);
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
      'nombre' => 'required|max:256',
      'tipo' => 'required',
      'contenido' => 'required'
    ]);

    $articulo = new articulo();
    $articulo->nombre = $request->nombre;
    $articulo->tipo = $request->tipo;
    $content = $request->contenido;

    try{
      $articulo->save();
      $id_articulo = DB::scalar("SELECT MAX(id_articulo) as id FROM articulosgenericos");
  
      $articulo->contenido = app(ImagenController::class)->store_for_summernote($content, "articulos", $id_articulo);
  
      $articulo->save();
      return redirect()->route('articulos')->with('message','Artículo '.$articulo->nombre.' añadido correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('articulos')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('articulos')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($id)
  {
    try {
      $articulo = Articulo::findorfail($id);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('articulos')->with('error', 'Se produjo un problema en la base de datos, no se pudo añadir.');
    } catch (Exception $excepcion) {
      return redirect()->route('articulos')->with('error', $excepcion->getMessage());
    }
    return view('articulos.show', ['articulo' => $articulo]);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $articulo = articulo::findorfail($id);
    return view('articulos.edit', ['articulo' => $articulo]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'contenido' => 'required',
      'tipo' => 'required'
    ]);

    try {
      $articulo = Articulo::findorfail($id);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('articulos.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo añadir.');
    } catch (Exception $excepcion) {
      return redirect()->route('articulos.index')->with('error', $excepcion->getMessage());
    }

    $articulo->nombre = $request->nombre;
    $articulo->tipo = $request->tipo;
    $content = $request->contenido;
    $articulo->contenido = app(ImagenController::class)->update_for_summernote($content, "articulos", $id);

    try {
      $articulo->save();
      return redirect()->route('articulos')->with('message', $articulo->nombre . ' editado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('articulos')->with('error', 'Se produjo un problema en la base de datos, no se pudo añadir.');
    } catch (Exception $excepcion) {
      return redirect()->route('articulos')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
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
      Articulo::destroy($request->id_borrar);

      return redirect()->route('articulos')->with('message', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('articulos')->with('error', 'Se produjo un problema en la base de datos, no se pudo borrar.' . $excepcion->getMessage());
    } catch (Exception $excepcion) {
      return redirect()->route('articulos')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display a listing of the resource searched.
   */
  public function search(Request $request)
  {
    $search = $request->input('search');
    try {
      $articulos = articulo::query()
        ->where('nombre', 'LIKE', "%{$search}%")
        ->orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $articulos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $articulos = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return view('articulos.index', ['articulos' => $articulos, 'orden' => 'asc', 'filtro_o' => 0]);
  }
}
