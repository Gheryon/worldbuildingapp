<?php

namespace App\Http\Controllers;

use App\Models\personaje;
use App\Models\Fecha;
use App\Models\organizacion;
use App\Models\Lugar;
use App\Models\Conflicto;
use App\Models\Religion;
use App\Models\Especie;
use App\Models\Asentamiento;
use App\Models\Construccion;
use Exception;
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
    $meses = array("Semana año nuevo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    try {
      $personaje = personaje::findorfail($id);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $personaje = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      return redirect()->route('personajes.index')->with('error', 'Error, no pudo encontrarse el personaje.');
    } catch (Exception $excepcion) {
      $personaje = ['error' => ['error' => $excepcion->getMessage()]];
      return redirect()->route('personajes.index')->with('error', 'Error, no pudo encontrarse el personaje.');
    }

    try {
      $especie = DB::select('select nombre from especies where id = ?', [$personaje->id_foranea_especie]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $especie = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $especie = ['error' => ['error' => $excepcion->getMessage()]];
    }

    if ($personaje->nacimiento != 0) {
      try {
        $nacimiento = Fecha::findorfail($personaje->nacimiento);
        if ($nacimiento->dia == 0 && $nacimiento->mes == 0) {
          $fecha_nacimiento = $nacimiento->anno;
        } else {
          $fecha_nacimiento = $nacimiento->dia . "-" . $meses[$nacimiento->mes] . "-" . $nacimiento->anno;
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $fecha_nacimiento = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $fecha_nacimiento = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $fecha_nacimiento = "Desconocido";
    }

    if ($personaje->fallecimiento != 0) {
      try {
        $fallecimiento = Fecha::findorfail($personaje->fallecimiento);
        if ($fallecimiento->dia == 0 && $fallecimiento->mes == 0) {
          $fecha_fallecimiento = $fallecimiento->anno;
        } else {
          $fecha_fallecimiento = $fallecimiento->dia . "-" . $meses[$fallecimiento->mes] . "-" . $fallecimiento->anno;
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $fecha_nacimiento = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $fecha_nacimiento = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $fecha_fallecimiento = "Desconocido";
    }

    if ($fecha_nacimiento != "Desconocido") {
      if ($fecha_fallecimiento != "Desconocido") {
        $dias_nacimiento = $nacimiento->anno * 365 + $nacimiento->mes * 30 + $nacimiento->dia;
        $dias_fallecimiento = $fallecimiento->anno * 365 + $fallecimiento->mes * 30 + $fallecimiento->dia;
        $edad = ($dias_fallecimiento - $dias_nacimiento) / 365;
        $edad = (int)$edad . " años.";
      } else {
        //si la fecha_fallecimiento==Desconocido, el personaje está vivo
        //la fecha actual del mundo se guarda en la id 1 de la db
        $fecha_actual = Fecha::find(1);
        $dias_nacimiento = $nacimiento->anno * 365 + $nacimiento->mes * 30 + $nacimiento->dia;
        $dias_actual = $fecha_actual->anno * 365 + $fecha_actual->mes * 30 + $fecha_actual->dia;
        $edad = ($dias_actual - $dias_nacimiento) / 365;
        $edad = (int)$edad . " años.";
      }
    } else {
      $edad = "Desconocida";
    }
    return view('personajes.show', ['vista' => $personaje, 'nacimiento' => $fecha_nacimiento, 'fallecimiento' => $fecha_fallecimiento, 'edad' => $edad, 'especie' => $especie[0]->nombre]);
  }

  public function show_organizacion($id)
  {
    $meses = array("Semana año nuevo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    try {
      $organizacion = organizacion::findorfail($id);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $organizacion = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $organizacion = ['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $tipo = DB::select('select nombre from tipo_organizacion where id = ?', [$organizacion->id_tipo_organizacion]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $tipo = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipo = ['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $subditos = organizacion::select('id_organizacion', 'nombre')->where('id_owner', '=', $id)->orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $subditos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $subditos = ['error' => ['error' => $excepcion->getMessage()]];
    }

    if ($organizacion->id_owner != 0) {
      try {
        $ownerb = DB::select('select nombre, id_organizacion from organizaciones where id_organizacion = ?', [$organizacion->id_owner]);
        $owner = $ownerb[0];
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $owner = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $owner = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $owner = 0;
    }

    if ($organizacion->id_ruler != 0 && $organizacion->id_ruler != null) {
      try {
        $soberanob = DB::select('select Nombre, id from personaje where id = ?', [$organizacion->id_ruler]);
        $soberano = $soberanob[0];
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $soberano = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $soberano = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $soberano = 0;
    }

    if ($organizacion->fundacion != 0 && $organizacion->fundacion != null) {
      try {
        $fundacion = Fecha::findorfail($organizacion->fundacion);
        if ($fundacion->dia && $fundacion->mes == 0) {
          $fecha_fundacion = $organizacion->anno;
        } else {
          $fecha_fundacion = $fundacion->dia . "-" . $meses[$fundacion->mes] . "-" . $fundacion->anno;
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $fecha_fundacion = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $fecha_fundacion = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $fecha_fundacion = "Desconocido";
    }

    if ($organizacion->disolucion != 0 && $organizacion->disolucion != null) {
      try {
        $disolucion = Fecha::findorfail($organizacion->disolucion);
        if ($disolucion->dia && $disolucion->mes == 0) {
          $fecha_disolucion = $organizacion->anno;
        } else {
          $fecha_disolucion = $disolucion->dia . "-" . $meses[$disolucion->mes] . "-" . $disolucion->anno;
        }
      } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
        $fecha_disolucion = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $fecha_disolucion = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $fecha_disolucion = "Desconocido";
    }

    try {
      $religiones = DB::select('SELECT religiones.nombre, religiones.id FROM religiones JOIN religion_presence ON religion_presence.religion=religiones.id WHERE religion_presence.organizacion = ?', [$id]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $religiones = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $religiones = ['error' => ['error' => $excepcion->getMessage()]];
    }

    return view('organizaciones.show', ['vista' => $organizacion, 'fundacion' => $fecha_fundacion, 'disolucion' => $fecha_disolucion, 'tipo' => $tipo[0]->nombre, 'owner' => $owner, 'soberano' => $soberano, 'subditos' => $subditos, 'religiones'=>$religiones]);
  }

  public function show_lugar($id)
  {
    try {
      $lugar = Lugar::findorfail($id);
      $tipo = DB::select('select nombre from tipo_lugar where id = ?', [$lugar->id_tipo_lugar]);

      if ($lugar->id_owner != 0) {
        $ownerb = DB::select('select nombre, id_organizacion from organizaciones where id_organizacion = ?', [$lugar->id_owner]);
        $owner = $ownerb[0];
      } else {
        $owner = 0;
      }

      return view('lugares.show', ['vista' => $lugar, 'tipo' => $tipo[0]->nombre, 'owner' => $owner]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      return redirect()->route('lugares.index')->with('error', 'Error, no pudo encontrarse el lugar.');
    }
  }

  public function show_conflicto($id)
  {
    $meses = array("Semana año nuevo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    try {
      $conflicto = Conflicto::findorfail($id);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('conflictos.index')->with('error', 'Error, no pudo encontrarse el conflicto.');
    } catch (Exception $excepcion) {
      return redirect()->route('conflictos.index')->with('error', 'Error, ' . $excepcion->getMessage());
    }

    try {
      $tipo = DB::select('select nombre from tipo_conflicto where id = ?', [$conflicto->id_tipo_conflicto]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $tipo = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipo = ['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $relacionados = Conflicto::select('id', 'nombre')->where('id_conflicto_padre', '=', $id)->orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $relacionados = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $relacionados = ['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      /*$atacantes=DB::table('organizaciones')
      ->select('organizaciones.nombre', 'organizaciones.id_organizacion')
      ->where('conflicto_beligerantes.id_organizacion', '=','organizaciones.id_organizacion')
      ->where('conflicto_beligerantes.id_conflicto', '=', $id)
      ->where('conflicto_beligerantes.lado', '=', 'atacante')
      ->orderBy('organizaciones.nombre', 'asc')->get();*/
      $atacantes = DB::select('SELECT *, organizaciones.nombre as nombre FROM conflicto_beligerantes JOIN organizaciones ON conflicto_beligerantes.id_organizacion=organizaciones.id_organizacion WHERE lado = "atacante" AND id_conflicto = ?', [$conflicto->id]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $atacantes = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $atacantes = ['error' => ['error' => $excepcion->getMessage()]];
    }
    try {
      $defensores = DB::select('SELECT *, organizaciones.nombre as nombre FROM conflicto_beligerantes JOIN organizaciones ON conflicto_beligerantes.id_organizacion=organizaciones.id_organizacion WHERE lado = "defensor" AND id_conflicto = ?', [$conflicto->id]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $defensores = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $defensores = ['error' => ['error' => $excepcion->getMessage()]];
    }

    try {
      $atacantesp = DB::select('SELECT personaje.id, personaje.Nombre as nombre FROM conflicto_personajes JOIN personaje ON conflicto_personajes.id_personaje=personaje.id WHERE rol = "atacante" AND id_conflicto = ?', [$conflicto->id]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $atacantesp = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $atacantesp = ['error' => ['error' => $excepcion->getMessage()]];
    }
    try {
      $defensoresp = DB::select('SELECT personaje.id, personaje.Nombre as nombre FROM conflicto_personajes JOIN personaje ON conflicto_personajes.id_personaje=personaje.id WHERE rol = "defensor" AND id_conflicto = ?', [$conflicto->id]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $defensoresp = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $defensoresp = ['error' => ['error' => $excepcion->getMessage()]];
    }

    if($conflicto->id_conflicto_padre!=0){
      try {
        $padreb = DB::select('select nombre, id from conflicto where id = ?', [$conflicto->id_conflicto_padre]);
        $padre=$padreb[0];
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $padre = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $padre = ['error' => ['error' => $excepcion->getMessage()]];
      }
    }else{
      $padre=0;
    }

    if ($conflicto->fecha_inicio != 0) {
      try {
        $fecha = Fecha::findorfail($conflicto->fecha_inicio);
        if ($fecha->dia == 0 && $fecha->mes == 0) {
          $fecha_inicio = $fecha->anno;
        } else {
          $fecha_inicio = $fecha->dia . "/" . $meses[$fecha->mes] . "/" . $fecha->anno;
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $fecha_inicio = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $fecha_inicio = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $fecha_inicio = "Desconocido";
    }

    if ($conflicto->fecha_fin != 0) {
      try {
        $fecha = Fecha::findorfail($conflicto->fecha_fin);
        if ($fecha->dia == 0 && $fecha->mes == 0) {
          $fecha_fin = $fecha->anno;
        } else {
          $fecha_fin = $fecha->dia . "/" . $meses[$fecha->mes] . "/" . $fecha->anno;
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $fecha_fin = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $fecha_fin = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $fecha_fin = "Desconocido";
    }

    return view('conflictos.show', ['vista' => $conflicto, 'tipo' => $tipo[0]->nombre, 'atacantes' => $atacantes, 'defensores' => $defensores, 'atacantesp' => $atacantesp, 'defensoresp' => $defensoresp, 'inicio' => $fecha_inicio, 'fin' => $fecha_fin, 'relacionados'=>$relacionados, 'padre' => $padre]);
  }

  public function show_construccion($id)
  {
    $meses = array("Semana año nuevo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    try {
      $construccion = Construccion::findorfail($id);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('construcciones.index')->with('error', 'Error, no pudo encontrarse el asentamiento.');
    } catch (Exception $excepcion) {
      return redirect()->route('construcciones.index')->with('error', 'Error, ' . $excepcion->getMessage());
    }

    try {
      $tipo = DB::select('select nombre from tipo_construccion where id = ?', [$construccion->tipo]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      $tipo = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipo = ['error' => ['error' => $excepcion->getMessage()]];
    }

    if ($construccion->ubicacion != 0) {
      try {
        $ownerb = DB::select('select nombre, id from asentamientos where id = ?', [$construccion->ubicacion]);
        $owner = $ownerb[0];
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $owner = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $owner = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $owner = 0;
    }

    if ($construccion->construccion != 0) {
      try {
        $fecha = Fecha::findorfail($construccion->construccion);
        if ($fecha->dia == 0 && $fecha->mes == 0) {
          $construccion_ini = $fecha->anno;
        } else {
          $construccion_ini = $fecha->dia . "/" . $meses[$fecha->mes] . "/" . $fecha->anno;
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $construccion_ini = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $construccion_ini = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $construccion_ini = "Desconocido";
    }

    if ($construccion->destruccion != 0) {
      try {
        $fecha = Fecha::findorfail($construccion->destruccion);
        if ($fecha->dia == 0 && $fecha->mes == 0) {
          $construccion_fin = $fecha->anno;
        } else {
          $construccion_fin = $fecha->dia . "/" . $meses[$fecha->mes] . "/" . $fecha->anno;
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $construccion_fin = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $construccion_fin = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $construccion_fin = "Desconocido";
    }
    return view('construcciones.show', ['vista' => $construccion, 'construccion' => $construccion_ini, 'destruccion' => $construccion_fin, 'tipo' => $tipo[0]->nombre, 'ubicacion' => $owner]);
  }

  public function show_religion($id)
  {
    try {
      $meses = array("Semana año nuevo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

      $religion = Religion::findorfail($id);

      if ($religion->fundacion != 0 && $religion->fundacion != null) {
        $fundacion = Fecha::find($religion->fundacion);
        if ($fundacion->dia && $fundacion->mes == 0) {
          $fecha_fundacion = $religion->anno;
        } else {
          $fecha_fundacion = $fundacion->dia . "-" . $meses[$fundacion->mes] . "-" . $fundacion->anno;
        }
      } else {
        $fecha_fundacion = "Desconocido";
      }
      if ($religion->disolucion != 0 && $religion->disolucion != null) {
        $disolucion = Fecha::find($religion->disolucion);
        if ($disolucion->dia && $disolucion->mes == 0) {
          $fecha_disolucion = $religion->anno;
        } else {
          $fecha_disolucion = $disolucion->dia . "-" . $meses[$disolucion->mes] . "-" . $disolucion->anno;
        }
      } else {
        $fecha_disolucion = "Desconocido";
      }

      return view('religiones.show', ['vista' => $religion, 'fundacion' => $fecha_fundacion, 'disolucion' => $fecha_disolucion]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      return redirect()->route('religiones.index')->with('error', 'Error, no pudo encontrarse la religión.');
    }
  }

  public function show_especie($id)
  {
    try {
      $especie = especie::findorfail($id);

      return view('especies.show', ['vista' => $especie]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
      return redirect()->route('especies.index')->with('error', 'Error, no pudo encontrarse la especie.');
    }
  }

  public function show_asentamiento($id)
  {
    $meses = array("Semana año nuevo", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    try {
      $asentamiento = Asentamiento::findorfail($id);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('asentamientos.index')->with('error', 'Error, no pudo encontrarse el asentamiento.');
    } catch (Exception $excepcion) {
      return redirect()->route('asentamientos.index')->with('error', 'Error, ' . $excepcion->getMessage());
    }

    try {
      $tipo = DB::select('select nombre from tipo_asentamiento where id = ?', [$asentamiento->id_tipo_asentamiento]);
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $tipo = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $tipo = ['error' => ['error' => $excepcion->getMessage()]];
    }

    if ($asentamiento->id_owner != 0) {
      try {
        $ownerb = DB::select('select nombre, id_organizacion from organizaciones where id_organizacion = ?', [$asentamiento->id_owner]);
        $owner = $ownerb[0];
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $owner = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $owner = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $owner = 0;
    }

    if ($asentamiento->fundacion != 0 && $asentamiento->fundacion != null) {
      try {
        $fundacion = Fecha::findorfail($asentamiento->fundacion);
        if ($fundacion->dia == 0 && $fundacion->mes == 0) {
          $fecha_fundacion = $fundacion->anno;
        } else {
          $fecha_fundacion = $fundacion->dia . "-" . $meses[$fundacion->mes] . "-" . $fundacion->anno;
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
        $fecha_fundacion = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $fecha_fundacion = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $fecha_fundacion = "Sin determinar";
    }

    if ($asentamiento->disolucion != 0 && $asentamiento->disolucion != null) {
      try {
        $disolucion = Fecha::findorfail($asentamiento->disolucion);
        if ($disolucion->dia == 0 && $disolucion->mes == 0) {
          $fecha_disolucion = $disolucion->anno;
        } else {
          $fecha_disolucion = $disolucion->dia . "-" . $meses[$disolucion->mes] . "-" . $disolucion->anno;
        }
      } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $excepcion) {
        $fecha_disolucion = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
      } catch (Exception $excepcion) {
        $fecha_disolucion = ['error' => ['error' => $excepcion->getMessage()]];
      }
    } else {
      $fecha_disolucion = "Sin determinar";
    }

    return view('asentamientos.show', ['vista' => $asentamiento, 'fundacion' => $fecha_fundacion, 'disolucion' => $fecha_disolucion, 'tipo' => $tipo[0]->nombre, 'owner' => $owner]);
  }
}
