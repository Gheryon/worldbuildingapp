<?php

namespace App\Http\Controllers;

use App\Models\Lugar;
use App\Models\imagen;
use App\Models\tipo_lugar;
use App\Http\Controllers\ImagenController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LugaresController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index($orden='asc', $tipo='0')
  {
    //obtener todos los lugares almacenados
    $lugares=Lugar::get_lugares($tipo, $orden);

    // Obtener todos los tipos de lugares almacenados
    $tipos = tipo_lugar::get_tipos_lugares();

    return view('lugares.index', ['lugares' => $lugares, 'tipos'=>$tipos, 'orden'=>$orden, 'tipo_o'=>$tipo]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    // Obtener todos los tipos de lugares almacenados
    $tipos = tipo_lugar::get_tipos_lugares();

    return view('lugares.create', ['tipos'=>$tipos]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre'=>'required|max:256',
      'select_tipo'=>'required',
    ]);

    try {
      $lugar=new lugar();
      $lugar->save();
  
      $id_lugar=DB::scalar("SELECT MAX(id) as id FROM lugares");
    }catch(\Illuminate\Database\QueryException $excepcion){
      Log::error('LugaresController->store: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('lugares.index')->with('error', 'Se produjo un problema en la base de datos.');
    }catch(Exception $excepcion){
      Log::error('LugaresController->store: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('lugares.index')->with('error', $excepcion->getMessage());
    }

    if($request->filled('nombre')){
      $lugar->Nombre=$request->nombre;
    }
    if($request->filled('otros_nombres')){
      $lugar->otros_nombres=$request->otros_nombres;
    }
    if($request->filled('descripcion_breve')){
      $lugar->descripcion_breve=app(ImagenController::class)->update_for_summernote($request->descripcion_breve, "lugares", $id_lugar);
    }
    if($request->filled('geografia')){
      $lugar->geografia=app(ImagenController::class)->update_for_summernote($request->geografia, "lugares", $id_lugar);
    }
    if($request->filled('ecosistema')){
      $lugar->ecosistema=app(ImagenController::class)->update_for_summernote($request->ecosistema, "lugares", $id_lugar);
    }
    if($request->filled('clima')){
      $lugar->clima=app(ImagenController::class)->update_for_summernote($request->clima, "lugares", $id_lugar);
    }
    if($request->filled('flora_fauna')){
      $lugar->flora_fauna=app(ImagenController::class)->update_for_summernote($request->flora_fauna, "lugares", $id_lugar);
    }
    if($request->filled('recursos')){
      $lugar->recursos=app(ImagenController::class)->update_for_summernote($request->recursos, "lugares", $id_lugar);
    }
    if($request->filled('historia')){
      $lugar->Historia=app(ImagenController::class)->update_for_summernote($request->historia, "lugares", $id_lugar);
    }
    if($request->filled('otros')){
      $lugar->otros=app(ImagenController::class)->update_for_summernote($request->otros, "lugares", $id_lugar);
    }

    $lugar->id_tipo_lugar=$request->select_tipo;
    
    try{
      $lugar->save();
      return redirect()->route('lugares.index')->with('message','Lugar añadido correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      Log::error('LugaresController->store: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('lugares.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      Log::error('LugaresController->store: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('lugares.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(lugar $lugar)
  {
    //se hace desde vistacontroller
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    // Obtener todos los tipos de lugares almacenados
    $tipos = tipo_lugar::get_tipos_lugares();


    $lugar = Lugar::get_lugar($id);
    if($lugar['error']??false){
      return redirect()->route('lugares')->with('error', $lugar['error']['error']);
    }

    return view('lugares.edit', ['lugar'=>$lugar, 'tipos'=>$tipos]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    $request->validate([
      'nombre'=>'required|max:256',
      'select_tipo'=>'required',
    ]);

     $lugar = Lugar::get_lugar($$request->id);
    if($lugar['error']??false){
      return redirect()->route('lugares')->with('error', $lugar['error']['error']);
    }
    
    if($request->filled('nombre')){
      $lugar->Nombre=$request->nombre;
    }
    if($request->filled('otros_nombres')){
      $lugar->otros_nombres=$request->otros_nombres;
    }
    if($request->filled('descripcion_breve')){
      $lugar->descripcion_breve=app(ImagenController::class)->update_for_summernote($request->descripcion_breve, "lugares", $request->id);
    }
    if($request->filled('geografia')){
      $lugar->geografia=app(ImagenController::class)->update_for_summernote($request->geografia, "lugares", $request->id);
    }
    if($request->filled('ecosistema')){
      $lugar->ecosistema=app(ImagenController::class)->update_for_summernote($request->ecosistema, "lugares", $request->id);
    }
    if($request->filled('clima')){
      $lugar->clima=app(ImagenController::class)->update_for_summernote($request->clima, "lugares", $request->id);
    }
    if($request->filled('flora_fauna')){
      $lugar->flora_fauna=app(ImagenController::class)->update_for_summernote($request->flora_fauna, "lugares", $request->id);
    }
    if($request->filled('recursos')){
      $lugar->recursos=app(ImagenController::class)->update_for_summernote($request->recursos, "lugares", $request->id);
    }
    if($request->filled('historia')){
      $lugar->Historia=app(ImagenController::class)->update_for_summernote($request->historia, "lugares", $request->id);
    }
    if($request->filled('otros')){
      $lugar->otros=app(ImagenController::class)->update_for_summernote($request->otros, "lugares", $request->id);
    }

    $lugar->id_tipo_lugar=$request->select_tipo;
    
    try{
      $lugar->save();
      return redirect()->route('lugares.index')->with('message', $lugar->nombre.' editado correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      Log::error('LugaresController->update: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('lugares.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      Log::error('LugaresController->update: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('lugares.index')->with('error', $excepcion->getMessage());
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
        ->where('table_owner', '=', 'lugares')
        ->where('owner', '=', $request->id_borrar)->get();
      
      foreach ($imagenes as $imagen) {
        if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
          unlink(public_path("/storage/imagenes/" . $imagen->nombre));
          //Storage::delete(asset($imagen->nombre));
        }
        imagen::destroy($imagen->id);
      }

      lugar::destroy($request->id_borrar);
      return redirect()->route('lugares.index')->with('message','lugar borrado correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      Log::error('LugaresController->destroy: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('lugares.index')->with('error','Se produjo un problema en la base de datos, no se pudo borrar.');
    }catch(Exception $excepcion){
      Log::error('LugaresController->destroy: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('lugares.index')->with('error',$excepcion->getMessage());
    }
  }

  /**
   * Display a listing of the resource searched.
   */
  public function search(Request $request)
  {
    $search = $request->input('search');
    try{
      $lugares=DB::table('lugares')
        ->leftjoin('tipo_lugar', 'lugares.id_tipo_lugar', '=', 'tipo_lugar.id')
        ->select('lugares.id', 'lugares.nombre', 'descripcion_breve', 'tipo_lugar.nombre AS tipo')
        ->where('lugares.nombre', 'LIKE', "%{$search}%")
        ->orderBy('lugares.nombre', 'asc')->get();
      
    }catch(\Illuminate\Database\QueryException $excepcion){
      Log::error('LugaresController->search: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $lugares=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      Log::error('LugaresController->search: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $lugares=['error' => ['error' => $excepcion->getMessage()]];
    }

    // Obtener todos los tipos de lugares almacenados
    $tipos = tipo_lugar::get_tipos_lugares();
    
    return view('lugares.index', ['lugares' => $lugares, 'tipos'=>$tipos, 'orden'=>'asc', 'tipo_o'=>0]);
  }
}
