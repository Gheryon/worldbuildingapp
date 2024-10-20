<?php

namespace App\Http\Controllers;

use App\Models\personaje;
use App\Models\Fecha;
use App\Models\organizacion;
use App\Models\Lugar;
use App\Models\Religion;
use App\Models\Especie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VistaController extends Controller
{
  //var $meses=array("Semana año nuevo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Display the specified personaje.
   */
  public function show_personaje($id)
  {
    try{
      $meses=array("Semana año nuevo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
      //$left=array("Nombre"=>"a", "Apellidos"=>"b", "nombreFamilia"=>"v", "DescripcionShort"=>"", "Descripcion"=>"", "Personalidad"=>"", "Deseos"=>"", "Miedo"=>"", "Magia"=>"", "educacion"=>"", "Historia"=>"", "Religion"=>"", "Familia"=>"", "Politica"=>"", "otros"=>"");
      //$right=array("retrato"=>"", "nombreEspecie"=>"", "sexo"=>"", "lugar_nacimiento"=>"", "nacimiento"=>"", "fallecimiento"=>"", "causa_fallecimiento"=>"");
      $personaje=personaje::findorfail($id);
      $especie = DB::select('select nombre from especies where id_especie = ?', [$personaje->id_foranea_especie]);
  
      if($personaje->nacimiento!=0){
        $nacimiento=Fecha::find($personaje->nacimiento);
        if($nacimiento->dia&&$nacimiento->mes==0){
          $fecha_nacimiento=$personaje->anno;
        }else{
          $fecha_nacimiento=$nacimiento->dia."-".$meses[$nacimiento->mes]."-".$nacimiento->anno;
        }
      }else{
        $fecha_nacimiento="Desconocido";
      }
      if($personaje->fallecimiento!=0){
        $fallecimiento=Fecha::find($personaje->fallecimiento);
        if($fallecimiento->dia&&$fallecimiento->mes==0){
          $fecha_fallecimiento=$personaje->anno;
        }else{
          $fecha_fallecimiento=$fallecimiento->dia."-".$meses[$fallecimiento->mes]."-".$fallecimiento->anno;
        }
      }else{
        $fecha_fallecimiento="Desconocido";
      }
  
      if($fecha_nacimiento!="Desconocido"){
        if($fecha_fallecimiento!="Desconocido"){
          $dias_nacimiento=$nacimiento->anno*365+$nacimiento->mes*30+$nacimiento->dia;
          $dias_fallecimiento=$fallecimiento->anno*365+$fallecimiento->mes*30+$fallecimiento->dia;
          $edad=($dias_fallecimiento-$dias_nacimiento)/365;
          $edad=(int)$edad." años.";
        }else{
          //si la fecha_fallecimiento==Desconocido, el personaje está vivo
          //la fecha actual del mundo se guarda en la id 1 de la db
          $fecha_actual=Fecha::find(1);
          $dias_nacimiento=$nacimiento->anno*365+$nacimiento->mes*30+$nacimiento->dia;
          $dias_actual=$fecha_actual->anno*365+$fecha_actual->mes*30+$fecha_actual->dia;
          $edad=($dias_actual-$dias_nacimiento)/365;
          $edad=(int)$edad." años.";
        }
      }else{
        $edad="Desconocida";
      }
      return view('personajes.show', ['vista'=>$personaje, 'nacimiento'=>$fecha_nacimiento, 'fallecimiento'=>$fecha_fallecimiento, 'edad'=>$edad, 'especie'=>$especie[0]->nombre]);
    }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion){
      return redirect()->route('personajes.index')->with('error','Error, no pudo encontrarse el personaje.');
    }
  }

  public function show_organizacion($id)
  {
    try{
      $meses=array("Semana año nuevo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  
      $organizacion=organizacion::findorfail($id);
      $tipo = DB::select('select nombre from tipo_organizacion where id = ?', [$organizacion->id_tipo_organizacion]);
  
      if($organizacion->id_owner!=0){
        $ownerb=DB::select('select nombre, id_organizacion from organizaciones where id_organizacion = ?', [$organizacion->id_owner]);
        $owner=$ownerb[0];
      }else{
        $owner=0;
      }
  
      if($organizacion->id_ruler!=0&&$organizacion->id_ruler!=null){
        $soberanob=DB::select('select Nombre, id from personaje where id = ?', [$organizacion->id_ruler]);
        $soberano=$soberanob[0];
      }else{
        $soberano=0;
      }
  
      if($organizacion->fundacion!=0&&$organizacion->fundacion!=null){
        $fundacion=Fecha::find($organizacion->fundacion);
        if($fundacion->dia&&$fundacion->mes==0){
          $fecha_fundacion=$organizacion->anno;
        }else{
          $fecha_fundacion=$fundacion->dia."-".$meses[$fundacion->mes]."-".$fundacion->anno;
        }
      }else{
        $fecha_fundacion="Desconocido";
      }
      if($organizacion->disolucion!=0&&$organizacion->disolucion!=null){
        $disolucion=Fecha::find($organizacion->disolucion);
        if($disolucion->dia&&$disolucion->mes==0){
          $fecha_disolucion=$organizacion->anno;
        }else{
          $fecha_disolucion=$disolucion->dia."-".$meses[$disolucion->mes]."-".$disolucion->anno;
        }
      }else{
        $fecha_disolucion="Desconocido";
      }
  
      return view('organizaciones.show', ['vista'=>$organizacion, 'fundacion'=>$fecha_fundacion, 'disolucion'=>$fecha_disolucion, 'tipo'=>$tipo[0]->nombre, 'owner'=>$owner, 'soberano'=>$soberano]);
    }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion){
      return redirect()->route('organizaciones.index')->with('error','Error, no pudo encontrarse la organización.');
    }
  }

  public function show_lugar($id)
  {
    try{
      $lugar=Lugar::findorfail($id);
      $tipo = DB::select('select nombre from tipo_lugar where id = ?', [$lugar->id_tipo_lugar]);
  
      if($lugar->id_owner!=0){
        $ownerb=DB::select('select nombre, id_organizacion from organizaciones where id_organizacion = ?', [$lugar->id_owner]);
        $owner=$ownerb[0];
      }else{
        $owner=0;
      }
  
      return view('lugares.show', ['vista'=>$lugar, 'tipo'=>$tipo[0]->nombre, 'owner'=>$owner]);
    }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion){
      return redirect()->route('lugares.index')->with('error','Error, no pudo encontrarse el lugar.');
    }
  }

  public function show_religion($id)
  {
    try{
      $meses=array("Semana año nuevo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  
      $religion=Religion::findorfail($id);
  
      if($religion->fundacion!=0&&$religion->fundacion!=null){
        $fundacion=Fecha::find($religion->fundacion);
        if($fundacion->dia&&$fundacion->mes==0){
          $fecha_fundacion=$religion->anno;
        }else{
          $fecha_fundacion=$fundacion->dia."-".$meses[$fundacion->mes]."-".$fundacion->anno;
        }
      }else{
        $fecha_fundacion="Desconocido";
      }
      if($religion->disolucion!=0&&$religion->disolucion!=null){
        $disolucion=Fecha::find($religion->disolucion);
        if($disolucion->dia&&$disolucion->mes==0){
          $fecha_disolucion=$religion->anno;
        }else{
          $fecha_disolucion=$disolucion->dia."-".$meses[$disolucion->mes]."-".$disolucion->anno;
        }
      }else{
        $fecha_disolucion="Desconocido";
      }
  
      return view('religiones.show', ['vista'=>$religion, 'fundacion'=>$fecha_fundacion, 'disolucion'=>$fecha_disolucion]);
    }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion){
      return redirect()->route('religiones.index')->with('error','Error, no pudo encontrarse la religión.');
    }
  }

  public function show_especie($id)
  {
    try{
      $especie=especie::findorfail($id);
  
      return view('especies.show', ['vista'=>$especie]);
    }catch(\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion){
      return redirect()->route('especies.index')->with('error','Error, no pudo encontrarse la especie.');
    }
  }
}
