<?php

namespace App\Http\Controllers;

use App\Models\Especie;
use App\Models\imagen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EspecieController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try{
      $especies=DB::table('especies')
        ->select('id', 'nombre')
        ->orderBy('nombre', 'asc')->get();

    }catch(\Illuminate\Database\QueryException $excepcion){
      $especies=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $especies=['error' => ['error' => $excepcion->getMessage()]];
    }

    return view('especies.index', ['especies' => $especies]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('especies.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre'=>'required|max:32',
      'estatus'=>'required'
    ]);

    $especie=new Especie();
    
    $especie->nombre=$request->nombre;
    $especie->save();

    $id_especie=DB::scalar("SELECT MAX(id) as id FROM especies");
    if($request->filled('edad')){
      $especie->edad=$request->edad;
    }
    if($request->filled('estatus')){
      $especie->estatus=$request->estatus;
    }
    if($request->filled('peso')){
      $especie->peso=$request->peso;
    }
    if($request->filled('altura')){
      $especie->altura=$request->altura;
    }
    if($request->filled('longitud')){
      $especie->longitud=$request->longitud;
    }
    if($request->filled('anatomia')){
      $especie->anatomia=app(ImagenController::class)->store_for_summernote($request->anatomia, "especies", $id_especie);
    }
    if($request->filled('alimentacion')){
      $especie->alimentacion=app(ImagenController::class)->store_for_summernote($request->alimentacion, "especies", $id_especie);
    }
    if($request->filled('reproduccion')){
      $especie->reproduccion=app(ImagenController::class)->store_for_summernote($request->reproduccion, "especies", $id_especie);
    }
    if($request->filled('distribucion')){
      $especie->distribucion=app(ImagenController::class)->store_for_summernote($request->distribucion, "especies", $id_especie);
    }
    if($request->filled('habilidades')){
      $especie->habilidades=app(ImagenController::class)->store_for_summernote($request->habilidades, "especies", $id_especie);
    }
    if($request->filled('domesticacion')){
      $especie->domesticacion=app(ImagenController::class)->store_for_summernote($request->domesticacion, "especies", $id_especie);
    }
    if($request->filled('explotacion')){
      $especie->explotacion=app(ImagenController::class)->store_for_summernote($request->explotacion, "especies", $id_especie);
    }
    if($request->filled('otros')){
      $especie->otros=app(ImagenController::class)->store_for_summernote($request->otros, "especies", $id_especie);
    }

    try{
      $especie->save();
      return redirect()->route('especies.index')->with('message','Especie '.$especie->nombre.' añadida correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('especies.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('especies.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $especie=Especie::findorfail($id);

    return view('especies.edit', ['especie'=>$especie]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Especie $especie)
  {
    $request->validate([
      'nombre'=>'required|max:32',
      'estatus'=>'required'
    ]);

    $especie=new Especie();
    
    $especie->nombre=$request->nombre;

    if($request->filled('edad')){
      $especie->edad=$request->edad;
    }
    if($request->filled('estatus')){
      $especie->estatus=$request->estatus;
    }
    if($request->filled('peso')){
      $especie->peso=$request->peso;
    }
    if($request->filled('altura')){
      $especie->altura=$request->altura;
    }
    if($request->filled('longitud')){
      $especie->longitud=$request->longitud;
    }
    if($request->filled('anatomia')){
      $especie->anatomia=app(ImagenController::class)->update_for_summernote($request->anatomia, "especies", $request->id);
    }
    if($request->filled('alimentacion')){
      $especie->alimentacion=app(ImagenController::class)->update_for_summernote($request->alimentacion, "especies", $request->id);
    }
    if($request->filled('reproduccion')){
      $especie->reproduccion=app(ImagenController::class)->update_for_summernote($request->reproduccion, "especies", $request->id);
    }
    if($request->filled('distribucion')){
      $especie->distribucion=app(ImagenController::class)->update_for_summernote($request->distribucion, "especies", $request->id);
    }
    if($request->filled('habilidades')){
      $especie->habilidades=app(ImagenController::class)->update_for_summernote($request->habilidades, "especies", $request->id);
    }
    if($request->filled('domesticacion')){
      $especie->domesticacion=app(ImagenController::class)->update_for_summernote($request->domesticacion, "especies", $request->id);
    }
    if($request->filled('explotacion')){
      $especie->explotacion=app(ImagenController::class)->update_for_summernote($request->explotacion, "especies", $request->id);
    }
    if($request->filled('otros')){
      $especie->otros=app(ImagenController::class)->update_for_summernote($request->otros, "especies", $request->id);
    }

    try{
      $especie->save();
      return redirect()->route('especies.index')->with('message','Especie '.$especie->nombre.' editada correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('especies.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('especies.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    try{
      //borrado de las imagenes que pueda haber de summernote
      $imagenes = DB::table('imagenes')
        ->select('id', 'nombre')
        ->where('table_owner', '=', 'especies')
        ->where('owner', '=', $request->id_borrar)->get();
      
      foreach ($imagenes as $imagen) {
        if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
        unlink(public_path("/storage/imagenes/" . $imagen->nombre));
        //Storage::delete(asset($imagen->nombre));
        }
        imagen::destroy($imagen->id);
      }
      Especie::destroy($request->id_borrar);

      return redirect()->route('especies.index')->with('message',$request->nombre_borrado.' borrado correctamente.');

    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('especies.index')->with('error','Se produjo un problema en la base de datos, no se pudo borrar.');
    }catch(Exception $excepcion){
      return redirect()->route('especies.index')->with('error',$excepcion->getMessage());
    }
  }
}
