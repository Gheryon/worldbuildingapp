<?php

namespace App\Http\Controllers;

use App\Models\personaje;
use App\Models\Fecha;
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
  }
}
