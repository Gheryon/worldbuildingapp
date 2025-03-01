<?php

namespace App\Http\Controllers;

use App\Models\personaje;
use App\Models\Fecha;
use App\Models\imagen;
use App\Http\Controllers\ImagenController;
use Especie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonajeController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index($orden='asc', $tipo='0')
  {
    try{
      if($tipo!=0){
        $personajes=DB::table('personaje')
          ->leftjoin('especies', 'personaje.id_foranea_especie', '=', 'especies.id')
          ->select('personaje.id', 'personaje.Nombre', 'Retrato', 'Sexo', 'id_foranea_especie', 'DescripcionShort', 'especies.nombre AS especie')
          ->where('personaje.id', '!=', 0)
          ->where('personaje.id_foranea_especie', '=', $tipo)
          ->orderBy('personaje.Nombre', $orden)->get();
      }else{
        $personajes=DB::table('personaje')
          ->leftjoin('especies', 'personaje.id_foranea_especie', '=', 'especies.id')
          ->select('personaje.id', 'personaje.Nombre', 'Retrato', 'Sexo', 'id_foranea_especie', 'DescripcionShort', 'especies.nombre AS especie')
          ->where('personaje.id', '!=', 0)
          ->orderBy('personaje.Nombre', $orden)->get();
      }
    }catch(\Illuminate\Database\QueryException $excepcion){
      $personajes=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $personajes=['error' => ['error' => $excepcion->getMessage()]];
    }
    try{
      $especies=DB::table('especies')->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
    }catch(\Illuminate\Database\QueryException $excepcion){
      $especies=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $especies=['error' => ['error' => $excepcion->getMessage()]];
    }
    return view('personajes.index', ['personajes' => $personajes, 'tipos'=>$especies, 'orden'=>$orden, 'tipo_o'=>$tipo]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    try{
      $especies=DB::table('especies')->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
    }catch(\Illuminate\Database\QueryException $excepcion){
      $especies=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $especies=['error' => ['error' => $excepcion->getMessage()]];
    }
    return view('personajes.create', ['especies'=>$especies]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre'=>'required|max:128',
      'sexo'=>'required',
      'select_especie'=>'required',
      'retrato' => 'file|image|mimes:jpg,png,gif|max:10240',
    ]);

    $personaje=new personaje();
    $personaje->Nombre=$request->nombre;
    $personaje->save();

    $id=DB::scalar("SELECT MAX(id_organizacion) as id FROM organizaciones");

    if($request->filled('apellidos')){
      $personaje->Apellidos=$request->apellidos;
    }
    if($request->filled('nombre_familia')){
      $personaje->nombreFamilia=$request->nombre_familia;
    }
    if($request->filled('lugar_nacimiento')){
      $personaje->lugar_nacimiento=$request->lugar_nacimiento;
    }
    if($request->filled('causa_fallecimiento')){
      $personaje->causa_fallecimiento=$request->causa_fallecimiento;
    }
    if($request->filled('descripcion')){
      $personaje->Descripcion=app(ImagenController::class)->store_for_summernote($request->descripcion, "personajes", $id);
    }
    if($request->filled('DescripcionShort')){
      $personaje->DescripcionShort=app(ImagenController::class)->store_for_summernote($request->DescripcionShort, "personajes", $id);
    }
    if($request->filled('salud')){
      $personaje->salud=app(ImagenController::class)->store_for_summernote($request->salud, "personajes", $id);
    }
    if($request->filled('personalidad')){
      $personaje->Personalidad=app(ImagenController::class)->store_for_summernote($request->personalidad, "personajes", $id);
    }
    if($request->filled('deseos')){
      $personaje->Deseos=app(ImagenController::class)->store_for_summernote($request->deseos, "personajes", $id);
    }
    if($request->filled('miedos')){
      $personaje->Miedos=app(ImagenController::class)->store_for_summernote($request->miedos, "personajes", $id);
    }
    if($request->filled('magia')){
      $personaje->Magia=app(ImagenController::class)->store_for_summernote($request->magia, "personajes", $id);
    }
    if($request->filled('educacion')){
      $personaje->educacion=app(ImagenController::class)->store_for_summernote($request->educacion, "personajes", $id);
    }
    if($request->filled('historia')){
      $personaje->Historia=app(ImagenController::class)->store_for_summernote($request->historia, "personajes", $id);
    }
    if($request->filled('religion')){
      $personaje->Religion=app(ImagenController::class)->store_for_summernote($request->religion, "personajes", $id);
    }
    if($request->filled('familia')){
      $personaje->Familia=app(ImagenController::class)->store_for_summernote($request->familia, "personajes", $id);;
    }
    if($request->filled('politica')){
      $personaje->Politica=app(ImagenController::class)->store_for_summernote($request->politica, "personajes", $id);;
    }
    if($request->filled('otros')){
      $personaje->otros=app(ImagenController::class)->store_for_summernote($request->otros, "personajes", $id);
    }

    $personaje->id_foranea_especie=$request->select_especie;
    $personaje->sexo=$request->sexo;
    
    try{
      //------------retrato----------//
      if($request->hasFile('retrato')){
        $path = $request->file('retrato')->store('retratos', 'public');
        $personaje->Retrato=basename($path);
      }else{
        $personaje->Retrato="default.png";
      }
  
      //------------fechas----------//
      $personaje->nacimiento=app(ConfigurationController::class)->store_fecha($request->input('dnacimiento', 0), $request->input('mnacimiento', 0), $request->input('anacimiento', 0), "personajes");
      $personaje->fallecimiento=app(ConfigurationController::class)->store_fecha($request->input('dfallecimiento', 0), $request->input('mfallecimiento', 0), $request->input('afallecimiento', 0), "personajes");

      $personaje->save();
      return redirect()->route('personajes.index')->with('message','Personaje añadido correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('personajes.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('personajes.index')->with('error', $excepcion->getMessage());
    }
    
  }

  /**
   * Display the specified resource.
   */
  public function show(personaje $personaje)
  {
    //se hace desde vistacontroller
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $fecha_fallecimiento=0;
    $fecha_nacimiento=0;
    try{
      $personaje=personaje::findorfail($id);
    }catch(\Illuminate\Database\QueryException $excepcion){
      $personaje=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $personaje=['error' => ['error' => $excepcion->getMessage()]];
    }

    try{
      $especies=DB::table('especies')->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
    }catch(\Illuminate\Database\QueryException $excepcion){
      $especies=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $especies=['error' => ['error' => $excepcion->getMessage()]];
    }
    
    if($personaje->nacimiento!=0){
      $fecha_nacimiento=Fecha::find($personaje->nacimiento);
    }else{
      $fecha_nacimiento=Fecha::find(0);
    }

    if($personaje->fallecimiento!=0){
      $fecha_fallecimiento=Fecha::find($personaje->fallecimiento);
    }else{
      $fecha_fallecimiento=Fecha::find(0);
    }
    return view('personajes.edit', ['personaje'=>$personaje, 'nacimiento'=>$fecha_nacimiento, 'fallecimiento'=>$fecha_fallecimiento, 'especies'=>$especies]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    $request->validate([
      'nombre'=>'required|max:128',
      'sexo'=>'required',
      'select_especie'=>'required',
      'retrato' => 'file|image|mimes:jpg,png,gif|max:10240',
    ]);

    try {
      $personaje=Personaje::findorfail($request->id);
    } catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('personajes.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('personajes.index')->with('error', $excepcion->getMessage());
    }
    if($request->filled('nombre')){
      $personaje->Nombre=$request->nombre;
    }
    if($request->filled('apellidos')){
      $personaje->Apellidos=$request->apellidos;
    }
    if($request->filled('nombre_familia')){
      $personaje->nombreFamilia=$request->nombre_familia;
    }
    if($request->filled('lugar_nacimiento')){
      $personaje->lugar_nacimiento=$request->lugar_nacimiento;
    }
    if($request->filled('causa_fallecimiento')){
      $personaje->causa_fallecimiento=$request->causa_fallecimiento;
    }
    if($request->filled('descripcion')){
      $personaje->Descripcion=app(ImagenController::class)->update_for_summernote($request->descripcion, "personajes", $request->id);
    }
    if($request->filled('DescripcionShort')){
      $personaje->DescripcionShort=app(ImagenController::class)->update_for_summernote($request->DescripcionShort, "personajes", $request->id);
    }
    if($request->filled('personalidad')){
      $personaje->Personalidad=app(ImagenController::class)->update_for_summernote($request->personalidad, "personajes", $request->id);
    }
    if($request->filled('salud')){
      $personaje->salud=app(ImagenController::class)->update_for_summernote($request->salud, "personajes", $request->id);
    }
    if($request->filled('deseos')){
      $personaje->Deseos=app(ImagenController::class)->update_for_summernote($request->deseos, "personajes", $request->id);
    }
    if($request->filled('miedos')){
      $personaje->Miedos=app(ImagenController::class)->update_for_summernote($request->miedos, "personajes", $request->id);
    }
    if($request->filled('magia')){
      $personaje->Magia=app(ImagenController::class)->update_for_summernote($request->magia, "personajes", $request->id);
    }
    if($request->filled('educacion')){
      $personaje->educacion=app(ImagenController::class)->update_for_summernote($request->educacion, "personajes", $request->id);
    }
    if($request->filled('historia')){
      $personaje->Historia=app(ImagenController::class)->update_for_summernote($request->historia, "personajes", $request->id);
    }
    if($request->filled('religion')){
      $personaje->Religion=app(ImagenController::class)->update_for_summernote($request->religion, "personajes", $request->id);
    }
    if($request->filled('familia')){
      $personaje->Familia=app(ImagenController::class)->update_for_summernote($request->familia, "personajes", $request->id);;
    }
    if($request->filled('politica')){
      $personaje->Politica=app(ImagenController::class)->update_for_summernote($request->politica, "personajes", $request->id);;
    }
    if($request->filled('otros')){
      $personaje->otros=app(ImagenController::class)->update_for_summernote($request->otros, "personajes", $request->id);
    }
    if($request->filled('select_especie')){
      $personaje->id_foranea_especie=$request->select_especie;
    }

    $personaje->sexo=$request->sexo;

    //------------fechas----------//
    $personaje->nacimiento=$request->input('id_nacimiento', 0);
    $personaje->fallecimiento=$request->input('id_fallecimiento', 0);
    
    try{
      //------------retrato----------//
      if($request->hasFile('retrato')){
        //el retrato anterior hay que borrarlo salvo que sea default.png
        if($personaje->Retrato!="default.png"){
          if (file_exists('storage/retratos/' . $personaje->Retrato)) {
            unlink('storage/retratos/' . $personaje->Retrato);
          }
        }
        $path = $request->file('retrato')->store('retratos', 'public');
        $personaje->Retrato=basename($path);
      }
      
      if($request->input('dnacimiento', 0)!=0){
        if($personaje->nacimiento!=0){
          //el personaje ya tenía fecha de nacimiento antes de editar
          app(ConfigurationController::class)->update_fecha($request->input('dnacimiento', 0), $request->input('mnacimiento', 0), $request->input('anacimiento', 0), $personaje->nacimiento);
        }else{
          //el personaje no tenía fecha de nacimiento antes de editar, hay que añadirla a la db.
          $personaje->nacimiento=app(ConfigurationController::class)->store_fecha($request->input('dnacimiento', 0), $request->input('mnacimiento', 0), $request->input('anacimiento', 0), "personajes");
        }
      }

      if($request->input('dfallecimiento', 0)!=0){
        if($personaje->fallecimiento!=0){
          //el personaje ya tenía fecha de fallecimiento antes de editar
          app(ConfigurationController::class)->update_fecha($request->input('dfallecimiento', 0), $request->input('mfallecimiento', 0), $request->input('afallecimiento', 0), $personaje->fallecimiento);
        }else{
          //el personaje no tenía fecha de fallecimiento antes de editar, hay que añadirla a la db.
          $personaje->nacimiento=app(ConfigurationController::class)->store_fecha($request->input('dfallecimiento', 0), $request->input('mfallecimiento', 0), $request->input('afallecimiento', 0), "personajes");
        }
      }

      $personaje->save();
      return redirect()->route('personajes.index')->with('message','Personaje editado correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('personajes.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
    }catch(Exception $excepcion){
      return redirect()->route('personajes.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    try{
      $nacimiento=DB::scalar("SELECT nacimiento FROM personaje where id = ?", [$request->id_personaje]);
      $fallecimiento=DB::scalar("SELECT fallecimiento FROM personaje where id = ?", [$request->id_personaje]);
      $retrato=DB::scalar("SELECT Retrato FROM personaje where id = ?", [$request->id_personaje]);
      
      if($nacimiento!=0){
        Fecha::destroy($nacimiento);
      }
      if($fallecimiento!=0){
        Fecha::destroy($fallecimiento);
      }
      if($retrato!="default.png"){
        if (file_exists('storage/retratos/' . $retrato)) {
          unlink('storage/retratos/' . $retrato);
        }
      }

      //borrado de las imagenes que pueda haber de summernote
      $imagenes = DB::table('imagenes')
        ->select('id', 'nombre')
        ->where('table_owner', '=', 'personajes')
        ->where('owner', '=', $request->id_borrar)->get();
      
      foreach ($imagenes as $imagen) {
        if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
          unlink(public_path("/storage/imagenes/" . $imagen->nombre));
          //Storage::delete(asset($imagen->nombre));
        }
        imagen::destroy($imagen->id);
      }

      Personaje::destroy($request->id_personaje);
      return redirect()->route('personajes.index')->with('message','Personaje borrado correctamente.');
    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('personajes.index')->with('error','Se produjo un problema en la base de datos, no se pudo borrar.');
    }catch(Exception $excepcion){
      return redirect()->route('personajes.index')->with('error',$excepcion->getMessage());
    }
  }

   /**
   * Display a listing of the resource searched.
   */
  public function search(Request $request)
  {
    $search = $request->input('search');
    try{
      $personajes=DB::table('personaje')
        ->leftjoin('especies', 'personaje.id_foranea_especie', '=', 'especies.id')
        ->select('personaje.id', 'personaje.Nombre', 'Retrato', 'Sexo', 'id_foranea_especie', 'DescripcionShort', 'especies.nombre AS especie')
        ->where('personaje.id', '!=', 0)
        ->where('personaje.Nombre', 'LIKE', "%{$search}%")
        ->orderBy('personaje.Nombre', 'asc')->get();
      
    }catch(\Illuminate\Database\QueryException $excepcion){
      $personajes=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $personajes=['error' => ['error' => $excepcion->getMessage()]];
    }
    try{
      $especies=DB::table('especies')->select('id', 'nombre')->orderBy('nombre', 'asc')->get();
    }catch(\Illuminate\Database\QueryException $excepcion){
      $especies=['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    }catch(Exception $excepcion){
      $especies=['error' => ['error' => $excepcion->getMessage()]];
    }
    return view('personajes.index', ['personajes' => $personajes, 'tipos'=>$especies, 'orden'=>'asc', 'tipo_o'=>0]);
  }
}
