<?php

namespace App\Http\Controllers;

use App\Models\personaje;
use App\Models\Fecha;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonajeController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    try{
      $personajes=DB::table('personaje')
        ->leftjoin('especies', 'personaje.id_foranea_especie', '=', 'especies.id_especie')
        ->select('id', 'personaje.Nombre', 'Retrato', 'Sexo', 'id_foranea_especie', 'DescripcionShort', 'especies.nombre')
        ->orderBy('personaje.Nombre', 'asc')->get();
      return view('personajes.index', ['personajes' => $personajes]);

    }catch(\Illuminate\Database\QueryException $excepcion){
      return view('personajes.index')->with('error', 'Se produjo un problema en la base de datos.');
    }catch(Exception $excepcion){
      return view('personajes.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $especies = DB::select('select id_especie, nombre from especies');

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
    $personaje->Apellidos=$request->apellidos;
    $personaje->nombreFamilia=$request->nombre_familia;
    $personaje->lugar_nacimiento=$request->lugar_nacimiento;
    $personaje->causa_fallecimiento=$request->causa_fallecimiento;
    $personaje->Descripcion=$request->descripcion;
    $personaje->DescripcionShort=$request->DescripcionShort;
    $personaje->Personalidad=$request->personalidad;
    $personaje->Deseos=$request->deseos;
    $personaje->Miedos=$request->miedos;
    $personaje->Magia=$request->magia;
    $personaje->educacion=$request->educacion;
    $personaje->Historia=$request->historia;
    $personaje->Religion=$request->religion;
    $personaje->Familia=$request->familia;
    $personaje->Politica=$request->politica;
    $personaje->id_foranea_especie=$request->select_especie;
    $personaje->sexo=$request->sexo;
    $personaje->otros=$request->otros;
    
    try{
      //------------retrato----------//
      if($request->hasFile('retrato')){
        $path = $request->file('retrato')->store('retratos', 'public');
        $personaje->Retrato=basename($path);
      }else{
        $personaje->Retrato="default.png";
      }
  
      //------------fechas----------//
      $fecha=new Fecha();
      $fecha->tabla="personajes";
      //si los input de las fechas no se introducen, la fecha es indeterminada, se establece a 0-0-0 por defecto
      $fecha->anno=$request->input('anacimiento', 0);
      $fecha->mes=$request->input('mnacimiento', 0);
      $fecha->dia=$request->input('dnacimiento', 0);
      if($fecha->anno==0&&$fecha->mes==0&&$fecha->dia==0){
        $personaje->nacimiento=0;
      }else{
        $fecha->save();
        $nacimiento=DB::scalar("SELECT MAX(id) as id FROM fechas");
        $personaje->nacimiento=$nacimiento;
      }
      $fecha=new Fecha();
      $fecha->tabla="personajes";
      $fecha->anno=$request->input('afallecimiento', 0);
      $fecha->mes=$request->input('mfallecimiento', 0);
      $fecha->dia=$request->input('dfallecimiento', 0);
      if($fecha->anno==0&&$fecha->mes==0&&$fecha->dia==0){
        $personaje->fallecimiento=0;
      }else{
        $fecha->save();
        $fallecimiento=DB::scalar("SELECT MAX(id) as id FROM fechas");
        $personaje->fallecimiento=$fallecimiento;
      }

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
    $personaje=personaje::findorfail($id);
    $especies = DB::select('select id_especie, nombre from especies');
    $fecha_fallecimiento=0;
    $fecha_nacimiento=0;
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

    $personaje=Personaje::find($request->id);
    $personaje->Nombre=$request->nombre;
    $personaje->Apellidos=$request->apellidos;
    $personaje->nombreFamilia=$request->nombre_familia;
    $personaje->lugar_nacimiento=$request->lugar_nacimiento;
    $personaje->causa_fallecimiento=$request->causa_fallecimiento;
    $personaje->Descripcion=$request->descripcion;
    $personaje->DescripcionShort=$request->DescripcionShort;
    $personaje->Personalidad=$request->personalidad;
    $personaje->Deseos=$request->deseos;
    $personaje->Miedos=$request->miedos;
    $personaje->Magia=$request->magia;
    $personaje->educacion=$request->educacion;
    $personaje->Historia=$request->historia;
    $personaje->Religion=$request->religion;
    $personaje->Familia=$request->familia;
    $personaje->Politica=$request->politica;
    $personaje->id_foranea_especie=$request->select_especie;
    $personaje->sexo=$request->sexo;
    $personaje->otros=$request->otros;

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
    }else{
      $personaje->Retrato=$request->retrato;
    }


    //------------fechas----------//
    $personaje->nacimiento=$request->input('id_nacimiento', 0);
    $personaje->fallecimiento=$request->input('id_fallecimiento', 0);
    
    try{
      
      if($request->input('dnacimiento', 0)!=0){
        if($personaje->nacimiento!=0){
          //el personaje ya tenía fecha de nacimiento antes de editar
          $fecha=Fecha::find($personaje->nacimiento);
          $fecha->anno=$request->input('anacimiento');
          $fecha->mes=$request->input('mnacimiento');
          $fecha->dia=$request->input('dnacimiento');
          $fecha->save();
        }else{
          //el personaje no tenía fecha de nacimiento antes de editar, hay que añadirla a la db.
          $fecha=new Fecha();
          $fecha->tabla="personajes";
          $fecha->anno=$request->input('anacimiento', 0);
          $fecha->mes=$request->input('mnacimiento', 0);
          $fecha->dia=$request->input('dnacimiento', 0);
          $fecha->save();
          $nacimiento=DB::scalar("SELECT MAX(id) as id FROM fechas");
          $personaje->nacimiento=$nacimiento;
        }

      }

      if($request->input('dfallecimiento', 0)!=0){
        if($personaje->fallecimiento!=0){
          //el personaje ya tenía fecha de fallecimiento antes de editar
          $fecha=Fecha::find($personaje->fallecimiento);
          $fecha->anno=$request->input('afallecimiento');
          $fecha->mes=$request->input('mfallecimiento');
          $fecha->dia=$request->input('dfallecimiento');
          $fecha->save();
        }else{
          //el personaje no tenía fecha de fallecimiento antes de editar, hay que añadirla a la db.
          $fecha=new Fecha();
          $fecha->tabla="personajes";
          $fecha->anno=$request->input('afallecimiento', 0);
          $fecha->mes=$request->input('mfallecimiento', 0);
          $fecha->dia=$request->input('dfallecimiento', 0);
          $fecha->save();
          $fallecimiento=DB::scalar("SELECT MAX(id) as id FROM fechas");
          $personaje->fallecimiento=$fallecimiento;
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
      Personaje::destroy($request->id_personaje);

    }catch(\Illuminate\Database\QueryException $excepcion){
      return redirect()->route('personajes.index')->with('error','Se produjo un problema en la base de datos, no se pudo borrar.');
    }catch(Exception $excepcion){
      return redirect()->route('personajes.index')->with('error',$excepcion->getMessage());
    }
    return redirect()->route('personajes.index')->with('message','Personaje borrado correctamente.');
  }
}
