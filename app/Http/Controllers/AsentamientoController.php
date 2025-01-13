<?php

namespace App\Http\Controllers;

use App\Models\Asentamiento;
use App\Models\tipo_asentamiento;
use App\Models\Fecha;
use App\Models\imagen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsentamientoController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index($orden='asc', $tipo='0')
  {
    try{
      if($tipo!=0){
        $asentamientos=DB::table('asentamientos')
          ->select('id', 'nombre')
          ->where('id_tipo_asentamiento', '=', $tipo)
          ->orderBy('nombre', $orden)->get();
      }
      else{
        $asentamientos=DB::table('asentamientos')
          ->select('id', 'nombre')
          ->orderBy('nombre', $orden)->get();
      }
    }catch(\Illuminate\Database\QueryException $excepcion){
      $asentamientos=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $asentamientos=['error' => ['error' => $excepcion->getMessage()]];
    }

    try{
      $tipos=tipo_asentamiento::orderBy('nombre', 'asc')->get();
    }catch (\Illuminate\Database\QueryException $excepcion) {
      $tipos=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipos=['error' => ['error' => $excepcion->getMessage()]];
    }

    return view('asentamientos.index', ['asentamientos' => $asentamientos, 'tipos'=>$tipos, 'orden'=>$orden, 'tipo_o'=>$tipo]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    try{
      $tipo_asentamiento =tipo_asentamiento::orderBy('nombre', 'asc')->get();

      $paises=DB::table('organizaciones')->select('id_organizacion', 'nombre')
      ->where('id_organizacion', '!=', 0)->orderBy('nombre', 'asc')->get();

      return view('asentamientos.create', ['tipo_asentamiento'=>$tipo_asentamiento, 'paises'=>$paises]);
      
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('asentamientos.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('asentamientos.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre'=>'required|max:128',
      'select_tipo'=>'required',
      'poblacion'=>'nullable|numeric',
    ]);

    $asentamiento=new Asentamiento();
    
    $asentamiento->nombre=$request->nombre;
    $asentamiento->save();

    $id_asentamiento=DB::scalar("SELECT MAX(id) as id FROM asentamientos");
    if($request->filled('gentilicio')){
      $asentamiento->gentilicio=$request->gentilicio;
    }
    if($request->filled('poblacion')){
      $asentamiento->poblacion=$request->poblacion;
    }
    if($request->filled('descripcion')){
      $asentamiento->descripcion=app(ImagenController::class)->store_for_summernote($request->descripcion, "asentamientos", $id_asentamiento);
    }
    if($request->filled('demografia')){
      $asentamiento->demografia=app(ImagenController::class)->store_for_summernote($request->demografia, "asentamientos", $id_asentamiento);
    }
    if($request->filled('gobierno')){
      $asentamiento->gobierno=app(ImagenController::class)->store_for_summernote($request->gobierno, "asentamientos", $id_asentamiento);
    }
    if($request->filled('infraestructura')){
      $asentamiento->infraestructura=app(ImagenController::class)->store_for_summernote($request->infraestructura, "asentamientos", $id_asentamiento);
    }
    if($request->filled('historia')){
      $asentamiento->historia=app(ImagenController::class)->store_for_summernote($request->historia, "asentamientos", $id_asentamiento);
    }
    if($request->filled('defensas')){
      $asentamiento->defensas=app(ImagenController::class)->store_for_summernote($request->defensas, "asentamientos", $id_asentamiento);
    }
    if($request->filled('cultura')){
      $asentamiento->cultura=app(ImagenController::class)->store_for_summernote($request->cultura, "asentamientos", $id_asentamiento);
    }
    if($request->filled('economia')){
      $asentamiento->economia=app(ImagenController::class)->store_for_summernote($request->economia, "asentamientos", $id_asentamiento);
    }
    if($request->filled('recursos')){
      $asentamiento->recursos=app(ImagenController::class)->store_for_summernote($request->recursos, "asentamientos", $id_asentamiento);
    }
    if($request->filled('geografia')){
      $asentamiento->geografia=app(ImagenController::class)->store_for_summernote($request->geografia, "asentamientos", $id_asentamiento);
    }
    if($request->filled('clima')){
      $asentamiento->clima=app(ImagenController::class)->store_for_summernote($request->clima, "asentamientos", $id_asentamiento);
    }
    if($request->filled('otros')){
      $asentamiento->otros=app(ImagenController::class)->store_for_summernote($request->otros, "asentamientos", $id_asentamiento);
    }

    $asentamiento->id_owner=$request->input('select_owner', 0);
    $asentamiento->id_tipo_asentamiento=$request->select_tipo;

    try{
      //------------fechas----------//
      $asentamiento->fundacion=app(ConfigurationController::class)->store_fecha($request->input('dfundacion', 0), $request->input('mfundacion', 0), $request->input('afundacion', 0), "asentamientos");
      $asentamiento->disolucion=app(ConfigurationController::class)->store_fecha($request->input('ddisolucion', 0), $request->input('mdisolucion', 0), $request->input('adisolucion', 0), "asentamientos");

      $asentamiento->save();
      return redirect()->route('asentamientos.index')->with('message','Asentamiento '.$asentamiento->nombre.' añadido correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('asentamientos.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('asentamientos.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Asentamiento $asentamiento)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    try{
      $asentamiento=Asentamiento::findorfail($id);
      $tipo_asentamiento =tipo_asentamiento::orderBy('nombre', 'asc')->get();

      $paises=DB::table('organizaciones')->select('id_organizacion', 'nombre')
      ->where('id_organizacion', '!=', 0)->orderBy('nombre', 'asc')->get();

      $fecha_disolucion=0;
      $fecha_fundacion=0;
      if($asentamiento->fundacion!=0){
        $fecha_fundacion=Fecha::find($asentamiento->fundacion);
      }else{
        $fecha_fundacion=Fecha::find(0);
      }
  
      if($asentamiento->disolucion!=0){
        $fecha_disolucion=Fecha::find($asentamiento->disolucion);
      }else{
        $fecha_disolucion=Fecha::find(0);
      }
  
      return view('asentamientos.edit', ['asentamiento'=>$asentamiento, 'fundacion'=>$fecha_fundacion, 'disolucion'=>$fecha_disolucion, 'paises'=>$paises, 'tipo_asentamiento'=>$tipo_asentamiento]);

    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('asentamientos.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('asentamientos.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    $request->validate([
      'nombre'=>'required|max:128',
      'select_tipo'=>'required',
      'poblacion'=>'numeric',
    ]);

    try {
      $asentamiento=Asentamiento::find($request->id);
    } catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('asentamientos.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('asentamientos.index')->with('error', $excepcion->getMessage());
    }
    
    $asentamiento->nombre=$request->nombre;
    if($request->filled('gentilicio')){
      $asentamiento->gentilicio=$request->gentilicio;
    }
    if($request->filled('poblacion')){
      $asentamiento->poblacion=$request->poblacion;
    }
    if($request->filled('descripcion')){
      $asentamiento->descripcion=app(ImagenController::class)->update_for_summernote($request->descripcion, "asentamientos", $request->id);
    }
    if($request->filled('demografia')){
      $asentamiento->demografia=app(ImagenController::class)->update_for_summernote($request->demografia, "asentamientos", $request->id);
    }
    if($request->filled('gobierno')){
      $asentamiento->gobierno=app(ImagenController::class)->update_for_summernote($request->gobierno, "asentamientos", $request->id);
    }
    if($request->filled('infraestructura')){
      $asentamiento->infraestructura=app(ImagenController::class)->update_for_summernote($request->infraestructura, "asentamientos", $request->id);
    }
    if($request->filled('historia')){
      $asentamiento->historia=app(ImagenController::class)->update_for_summernote($request->historia, "asentamientos", $request->id);
    }
    if($request->filled('defensas')){
      $asentamiento->defensas=app(ImagenController::class)->update_for_summernote($request->defensas, "asentamientos", $request->id);
    }
    if($request->filled('cultura')){
      $asentamiento->cultura=app(ImagenController::class)->update_for_summernote($request->cultura, "asentamientos", $request->id);
    }
    if($request->filled('economia')){
      $asentamiento->economia=app(ImagenController::class)->update_for_summernote($request->economia, "asentamientos", $request->id);
    }
    if($request->filled('recursos')){
      $asentamiento->recursos=app(ImagenController::class)->update_for_summernote($request->recursos, "asentamientos", $request->id);
    }
    if($request->filled('geografia')){
      $asentamiento->geografia=app(ImagenController::class)->update_for_summernote($request->geografia, "asentamientos", $request->id);
    }
    if($request->filled('clima')){
      $asentamiento->clima=app(ImagenController::class)->update_for_summernote($request->clima, "asentamientos", $request->id);
    }
    if($request->filled('otros')){
      $asentamiento->otros=app(ImagenController::class)->update_for_summernote($request->otros, "asentamientos", $request->id);
    }

    $asentamiento->id_owner=$request->input('select_owner', 0);
    $asentamiento->id_tipo_asentamiento=$request->select_tipo;

    try{
      //------------fechas----------//
      if($request->input('afundacion', 0)!=0){
        if($asentamiento->fundacion!=0){
          //el asentamiento ya tenía fecha de fundacion antes de editar
          app(ConfigurationController::class)->update_fecha($request->input('dfundacion', 0), $request->input('mfundacion', 0), $request->input('afundacion', 0), $asentamiento->fundacion);
        }else{
          //el asentamiento no tenía fecha de fundacion antes de editar, hay que añadirla a la db.
          $asentamiento->fundacion=app(ConfigurationController::class)->store_fecha($request->input('dfundacion', 0), $request->input('mfundacion', 0), $request->input('afundacion', 0), "asentamientos");
        }
      }

      if($request->input('adisolucion', 0)!=0){
        if($asentamiento->disolucion!=0){
          //el asentamiento ya tenía fecha de disolucion antes de editar
          app(ConfigurationController::class)->update_fecha($request->input('ddisolucion', 0), $request->input('mdisolucion', 0), $request->input('adisolucion', 0), $asentamiento->disolucion);
        }else{
          //el asentamiento no tenía fecha de disolucion antes de editar, hay que añadirla a la db.
          $asentamiento->disolucion=app(ConfigurationController::class)->store_fecha($request->input('ddisolucion', 0), $request->input('mdisolucion', 0), $request->input('adisolucion', 0), "asentamientos");
        }
      }

      $asentamiento->save();
      return redirect()->route('asentamientos.index')->with('message','Asentamiento '.$asentamiento->nombre.' editado correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('asentamientos.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('asentamientos.index')->with('error', $excepcion->getMessage());
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
      try{
        $fundacion=DB::scalar("SELECT fundacion FROM asentamientos where id = ?", [$request->id_borrar]);
        $disolucion=DB::scalar("SELECT disolucion FROM asentamientos where id = ?", [$request->id_borrar]);

        //si fundacion/disolucion != 0, la organizacion tiene fecha establecida, hay que borrar
        if($fundacion!=0){
          Fecha::destroy($fundacion);
        }
        if($disolucion!=0){
          Fecha::destroy($disolucion);
        }

        //borrado de las imagenes que pueda haber de summernote
        $imagenes = DB::table('imagenes')
          ->select('id', 'nombre')
          ->where('table_owner', '=', 'asentamientos')
          ->where('owner', '=', $request->id_borrar)->get();
        
        foreach ($imagenes as $imagen) {
          if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
          unlink(public_path("/storage/imagenes/" . $imagen->nombre));
          //Storage::delete(asset($imagen->nombre));
          }
          imagen::destroy($imagen->id);
        }
        Asentamiento::destroy($request->id_borrar);
        return redirect()->route('asentamientos.index')->with('message',$request->nombre_borrado.' borrado correctamente.');
  
      }catch(\Illuminate\Database\QueryException $excepcion){
        return redirect()->route('asentamientos.index')->with('error','Se produjo un problema en la base de datos, no se pudo borrar.');
      }catch(Exception $excepcion){
        return redirect()->route('asentamientos.index')->with('error',$excepcion->getMessage());
      }
    }

    /**
   * Display a listing of the resource searched.
   */
  
   public function search(Request $request)
  {
    $search = $request->input('search');
    try{
      $asentamientos=DB::table('asentamientos')
        ->select('id', 'nombre')
        ->where('nombre', 'LIKE', "%{$search}%")
        ->orderBy('nombre', 'asc')->get();
      
    }catch(\Illuminate\Database\QueryException $excepcion){
      $asentamientos=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $asentamientos=['error' => ['error' => $excepcion->getMessage()]];
    }
    return view('asentamientos.index', ['asentamientos' => $asentamientos]);
  }
}
