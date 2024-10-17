<?php

namespace App\Http\Controllers;

use App\Models\organizacion;
use App\Models\tipo_organizacion;
use App\Models\Fecha;
use App\Models\imagen;
use App\Http\Controllers\ImagenController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizacionController extends Controller
{
    /**
     * Muestra una lista de las organizaciones guardadas.
     */
    public function index()
    {
      $organizaciones=DB::table('organizaciones')
        ->join('tipo_organizacion', 'organizaciones.id_tipo_organizacion', '=', 'tipo_organizacion.id')
        ->select('organizaciones.id_organizacion', 'organizaciones.nombre', 'organizaciones.escudo', 'organizaciones.descripcionBreve', 'tipo_organizacion.nombre AS tipo')
        ->where('organizaciones.id_organizacion', '!=', 0)
        ->orderBy('organizaciones.nombre', 'asc')->get();
      return view('organizaciones.index', ['organizaciones' => $organizaciones]);
    }

    /**
     * Mostrar formulario para crear una nueva organización.
     */
    public function create()
    {
      $tipo_organizacion =tipo_organizacion::orderBy('nombre', 'asc')->get();

      $personajes=DB::table('personaje')->select('id', 'Nombre')->orderBy('Nombre', 'asc')->get();

      $paises=DB::table('organizaciones')->select('id_organizacion', 'nombre')->orderBy('nombre', 'asc')->get();

      return view('organizaciones.create', ['tipo_organizacion'=>$tipo_organizacion, 'personajes'=>$personajes, 'paises'=>$paises]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $request->validate([
      'nombre'=>'required|max:128',
      'select_tipo'=>'required',
      'escudo' => 'file|image|mimes:jpg,png,gif|max:10240',
      ]);

      $organizacion=new organizacion();
      $organizacion->nombre=$request->nombre;
      $organizacion->gentilicio=$request->gentilicio;
      $organizacion->capital=$request->capital;
      $organizacion->lema=$request->lema;

      //inputs de summernote
      if($request->filled('DescripcionShort')){
        $organizacion->descripcionBreve=app(ImagenController::class)->update_for_summernote($request->DescripcionShort, "organizaciones", $request->id);
      }
      if($request->filled('historia')){
        $organizacion->historia=app(ImagenController::class)->update_for_summernote($request->historia, "organizaciones", $request->id);;
      }
      if($request->filled('politica')){
        $organizacion->politicaExteriorInterior=app(ImagenController::class)->update_for_summernote($request->politica, "organizaciones", $request->id);;
      }
      if($request->filled('militar')){
        $organizacion->militar=app(ImagenController::class)->update_for_summernote($request->militar, "organizaciones", $request->id);;
      }
      if($request->filled('estructura')){
        $organizacion->estructura=app(ImagenController::class)->update_for_summernote($request->estructura, "organizaciones", $request->id);;
      }
      if($request->filled('territorio')){
        $organizacion->territorio=app(ImagenController::class)->update_for_summernote($request->territorio, "organizaciones", $request->id);;
      }
      if($request->filled('frontera')){
        $organizacion->frontera=app(ImagenController::class)->update_for_summernote($request->frontera, "organizaciones", $request->id);;
      }
      if($request->filled('religion')){
        $organizacion->religion=app(ImagenController::class)->update_for_summernote($request->religion, "organizaciones", $request->id);;
      }
      if($request->filled('demografia')){
        $organizacion->demografia=app(ImagenController::class)->update_for_summernote($request->demografia, "organizaciones", $request->id);;
      }
      if($request->filled('cultura')){
        $organizacion->cultura=app(ImagenController::class)->update_for_summernote($request->cultura, "organizaciones", $request->id);;
      }
      if($request->filled('educacion')){
        $organizacion->educacion=app(ImagenController::class)->update_for_summernote($request->educacion, "organizaciones", $request->id);;
      }
      if($request->filled('tecnologia')){
        $organizacion->tecnologia=app(ImagenController::class)->update_for_summernote($request->tecnologia, "organizaciones", $request->id);;
      }
      if($request->filled('economia')){
        $organizacion->economia=app(ImagenController::class)->update_for_summernote($request->economia, "organizaciones", $request->id);;
      }
      if($request->filled('recursos')){
        $organizacion->recursosNaturales=app(ImagenController::class)->update_for_summernote($request->recursos, "organizaciones", $request->id);;
      }
      if($request->filled('otros')){
        $organizacion->otros=app(ImagenController::class)->update_for_summernote($request->otros, "organizaciones", $request->id);;
      }
      
      $organizacion->id_ruler=$request->input('soberano', 0);
      $organizacion->id_owner=$request->input('owner', 0);
      $organizacion->id_tipo_organizacion=$request->select_tipo;

      try{
        //------------escudo----------//
        if($request->hasFile('escudo')){
          $path = $request->file('escudo')->store('escudos', 'public');
          $organizacion->escudo=basename($path);
        }else{
          $organizacion->escudo="default.png";
        }

        //------------fechas----------//
        $organizacion->fundacion=app(ConfigurationController::class)->store_fecha($request->input('dfundacion', 0), $request->input('mfundacion', 0), $request->input('afundacion', 0), "organizaciones");
        $organizacion->disolucion=app(ConfigurationController::class)->store_fecha($request->input('ddisolucion', 0), $request->input('mdisolucion', 0), $request->input('adisolucion', 0), "organizaciones");

        $organizacion->save();
        return redirect()->route('organizaciones.index')->with('message','Organización añadida correctamente.');
      }catch(\Illuminate\Database\QueryException $excepcion){
        return redirect()->route('organizaciones.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.'.$excepcion->getMessage());
      }catch(Exception $excepcion){
        return redirect()->route('organizaciones.index')->with('error', $excepcion->getMessage());
      }

    }

    /**
     * Display the specified resource.
     */
    public function show(organizacion $organizacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
      $organizacion=organizacion::findorfail($id);
      $tipo_organizacion =tipo_organizacion::orderBy('nombre', 'asc')->get();

      $personajes=DB::table('personaje')->select('id', 'Nombre')->orderBy('Nombre', 'asc')->get();

      $paises=DB::table('organizaciones')->select('id_organizacion', 'nombre')->orderBy('nombre', 'asc')->get();

      $fecha_fundacion=0;
      $fecha_disolucion=0;
      if($organizacion->fundacion!=0){
        $fecha_fundacion=Fecha::find($organizacion->fundacion);
      }else{
        $fecha_fundacion=Fecha::find(0);
      }

      if($organizacion->disolucion!=0){
        $fecha_disolucion=Fecha::find($organizacion->disolucion);
      }else{
        $fecha_disolucion=Fecha::find(0);
      }
      
      return view('organizaciones.edit', ['organizacion'=>$organizacion, 'fundacion'=>$fecha_fundacion, 'disolucion'=>$fecha_disolucion, 'tipo_organizacion'=>$tipo_organizacion, 'personajes'=>$personajes, 'paises'=>$paises]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
      $request->validate([
        'nombre'=>'required|max:128',
        'select_tipo'=>'required',
        'escudo' => 'file|image|mimes:jpg,png,gif|max:10240',
      ]);
  
      $organizacion=organizacion::find($request->id);
      $organizacion->nombre=$request->nombre;
      $organizacion->gentilicio=$request->gentilicio;
      $organizacion->capital=$request->capital;
      $organizacion->lema=$request->lema;

      //inputs de summernote
      if($request->filled('DescripcionShort')){
        $organizacion->descripcionBreve=app(ImagenController::class)->update_for_summernote($request->DescripcionShort, "organizaciones", $request->id);
      }
      if($request->filled('historia')){
        $organizacion->historia=app(ImagenController::class)->update_for_summernote($request->historia, "organizaciones", $request->id);;
      }
      if($request->filled('politica')){
        $organizacion->politicaExteriorInterior=app(ImagenController::class)->update_for_summernote($request->politica, "organizaciones", $request->id);;
      }
      if($request->filled('militar')){
        $organizacion->militar=app(ImagenController::class)->update_for_summernote($request->militar, "organizaciones", $request->id);;
      }
      if($request->filled('estructura')){
        $organizacion->estructura=app(ImagenController::class)->update_for_summernote($request->estructura, "organizaciones", $request->id);;
      }
      if($request->filled('territorio')){
        $organizacion->territorio=app(ImagenController::class)->update_for_summernote($request->territorio, "organizaciones", $request->id);;
      }
      if($request->filled('frontera')){
        $organizacion->frontera=app(ImagenController::class)->update_for_summernote($request->frontera, "organizaciones", $request->id);;
      }
      if($request->filled('religion')){
        $organizacion->religion=app(ImagenController::class)->update_for_summernote($request->religion, "organizaciones", $request->id);;
      }
      if($request->filled('demografia')){
        $organizacion->demografia=app(ImagenController::class)->update_for_summernote($request->demografia, "organizaciones", $request->id);;
      }
      if($request->filled('cultura')){
        $organizacion->cultura=app(ImagenController::class)->update_for_summernote($request->cultura, "organizaciones", $request->id);;
      }
      if($request->filled('educacion')){
        $organizacion->educacion=app(ImagenController::class)->update_for_summernote($request->educacion, "organizaciones", $request->id);;
      }
      if($request->filled('tecnologia')){
        $organizacion->tecnologia=app(ImagenController::class)->update_for_summernote($request->tecnologia, "organizaciones", $request->id);;
      }
      if($request->filled('economia')){
        $organizacion->economia=app(ImagenController::class)->update_for_summernote($request->economia, "organizaciones", $request->id);;
      }
      if($request->filled('recursos')){
        $organizacion->recursosNaturales=app(ImagenController::class)->update_for_summernote($request->recursos, "organizaciones", $request->id);;
      }
      if($request->filled('otros')){
        $organizacion->otros=app(ImagenController::class)->update_for_summernote($request->otros, "organizaciones", $request->id);;
      }

      $organizacion->id_ruler=$request->input('soberano', 0);
      $organizacion->id_owner=$request->input('owner', 0);
      $organizacion->id_tipo_organizacion=$request->select_tipo;
  
      //------------escudo----------//
      if($request->hasFile('escudo')){
        //el escudo anterior hay que borrarlo salvo que sea default.png
        if($organizacion->escudo!="default.png"){
          if (file_exists('storage/escudos/' . $organizacion->escudo)) {
            unlink('storage/escudos/' . $organizacion->escudo);
          }
        }
        $path = $request->file('escudo')->store('escudos', 'public');
        $organizacion->escudo=basename($path);
      }

      //------------fechas----------//
      $organizacion->fundacion=$request->input('id_fundacion', 0);
      $organizacion->disolucion=$request->input('id_disolucion', 0);
      
      try{
        if($request->input('afundacion', 0)!=0){
          if($organizacion->fundacion!=0){
            //la organizacion ya tenía fecha de fundacion antes de editar
            app(ConfigurationController::class)->update_fecha($request->input('dfundacion', 0), $request->input('mfundacion', 0), $request->input('afundacion', 0), $organizacion->fundacion);
          }else{
            //la organizacion no tenía fecha de fundacion antes de editar, hay que añadirla a la db.
            $organizacion->fundacion=app(ConfigurationController::class)->store_fecha($request->input('dfundacion', 0), $request->input('mfundacion', 0), $request->input('afundacion', 0), "organizaciones");
          }
        }
  
        if($request->input('adisolucion', 0)!=0){
          if($organizacion->disolucion!=0){
            //el organizacion ya tenía fecha de disolucion antes de editar
            app(ConfigurationController::class)->update_fecha($request->input('ddisolucion', 0), $request->input('mdisolucion', 0), $request->input('adisolucion', 0), $organizacion->disolucion);
          }else{
            //el organizacion no tenía fecha de disolucion antes de editar, hay que añadirla a la db.
            $organizacion->disolucion=app(ConfigurationController::class)->store_fecha($request->input('ddisolucion', 0), $request->input('mdisolucion', 0), $request->input('adisolucion', 0), "organizaciones");
          }
        }
  
        $organizacion->save();
        return redirect()->route('organizaciones.index')->with('message',$organizacion->nombre.' editado correctamente.');
      }catch(\Illuminate\Database\QueryException $excepcion){
        return redirect()->route('organizaciones.index')->with('error','Se produjo un problema en la base de datos, no se pudo añadir.');
      }catch(Exception $excepcion){
        return redirect()->route('organizaciones.index')->with('error', $excepcion->getMessage());
      }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
      try{
        $fundacion=DB::scalar("SELECT fundacion FROM organizaciones where id_organizacion = ?", [$request->id_borrar]);
        $disolucion=DB::scalar("SELECT disolucion FROM organizaciones where id_organizacion = ?", [$request->id_borrar]);
        $escudo=DB::scalar("SELECT escudo FROM organizaciones where id_organizacion = ?", [$request->id_borrar]);

        //si fundacion/disolucion != 0, la organizacion tiene fecha establecida, hay que borrar
        if($fundacion!=0){
          Fecha::destroy($fundacion);
        }
        if($disolucion!=0){
          Fecha::destroy($disolucion);
        }
        
        if($escudo!="default.png"){
          if (file_exists('storage/escudos/' . $escudo)) {
            unlink('storage/escudos/' . $escudo);
          }
        }

        //borrado de las imagenes que pueda haber de summernote
        $imagenes = DB::table('imagenes')
          ->select('id', 'nombre')
          ->where('table_owner', '=', 'organizaciones')
          ->where('owner', '=', $request->id_borrar)->get();
        
        foreach ($imagenes as $imagen) {
          if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
          unlink(public_path("/storage/imagenes/" . $imagen->nombre));
          //Storage::delete(asset($imagen->nombre));
          }
          imagen::destroy($imagen->id);
        }
        Organizacion::destroy($request->id_borrar);
        return redirect()->route('organizaciones.index')->with('message',$request->nombre_borrado.' borrado correctamente.');
  
      }catch(\Illuminate\Database\QueryException $excepcion){
        return redirect()->route('organizaciones.index')->with('error','Se produjo un problema en la base de datos, no se pudo borrar.');
      }catch(Exception $excepcion){
        return redirect()->route('organizaciones.index')->with('error',$excepcion->getMessage());
      }
    }
}
