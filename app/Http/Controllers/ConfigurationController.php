<?php

namespace App\Http\Controllers;

use App\Models\Nombres;
use App\Models\configuration;
use App\Models\tipo_asentamiento;
use App\Models\TipoConflicto;
use App\Models\TipoConstruccion;
use App\Models\tipo_lugar;
use App\Models\tipo_organizacion;
use App\Models\lineas_temporales;
use App\Models\Fecha;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class ConfigurationController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //Obtener el nombre del mundo
    $nombre_mundo = Nombres::get_nombre_mundo();

    //Obtener la fecha actual del mundo
    $fecha = Fecha::get_fecha_mundo();

    // Obtener todos los tipos de asentamiento almacenados
    $tipos_asentamiento = tipo_asentamiento::get_tipos_asentamientos();

    // Obtener todos los tipos de conflictos almacenados
    $tipos_conflicto = TipoConflicto::get_tipos_conflictos();

    // Obtener todos los tipos de construcciones almacenados
    $tipos_construccion = TipoConstruccion::get_tipos_construcciones();

    // Obtener todos los tipos de lugares almacenados
    $tipos_lugar = tipo_lugar::get_tipos_lugares();

    // Obtener todos los tipos de organizacion almacenados
    $tipos_organizacion = tipo_organizacion::get_tipos_organizaciones();

    //actualmente sin uso, pero se deja para futuras implementaciones
    try {
      $lineas_temporales = lineas_temporales::orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      $lineas_temporales = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      $lineas_temporales = ['error' => ['error' => $excepcion->getMessage()]];
    }
    return view('config.index', ['tipos_asentamiento' => $tipos_asentamiento, 'tipos_conflicto' => $tipos_conflicto, 'tipos_construccion' => $tipos_construccion, 'tipos_lugar' => $tipos_lugar, 'tipos_organizaciones' => $tipos_organizacion, 'lineas_temporales' => $lineas_temporales, 'Nombre_mundo' => $nombre_mundo, 'fecha' => $fecha]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store_tipo_asentamiento(Request $request)
  {
    $request->validate([
      'nuevo_tipo_asentamiento' => 'required|max:128',
    ]);

    try {
      $nuevo = new tipo_asentamiento();
      $nuevo->nombre = $request->input('nuevo_tipo_asentamiento');
      $nuevo->save();

      return redirect()->route('config.index')->with('message', $nuevo->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  public function store_tipo_conflicto(Request $request)
  {
    $request->validate([
      'nuevo_tipo_conflicto' => 'required|max:128',
    ]);

    $nuevo = new TipoConflicto();
    $nuevo->nombre = $request->input('nuevo_tipo_conflicto');
    $nuevo->save();

    return redirect()->route('config.index')->with('message', $nuevo->nombre . ' añadido correctamente.');
  }

  public function store_tipo_construccion(Request $request)
  {
    $request->validate([
      'nuevo_tipo_construccion' => 'required|max:128',
    ]);

    $nuevo = new TipoConstruccion();
    $nuevo->nombre = $request->input('nuevo_tipo_construccion');
    $nuevo->save();

    return redirect()->route('config.index')->with('message', $nuevo->nombre . ' añadido correctamente.');
  }

  public function store_tipo_lugar(Request $request)
  {
    $request->validate([
      'nuevo_tipo_lugar' => 'required|max:128',
    ]);

    $nuevo = new tipo_lugar();
    $nuevo->nombre = $request->input('nuevo_tipo_lugar');
    $nuevo->save();

    return redirect()->route('config.index')->with('message', $nuevo->nombre . ' añadido correctamente.');
  }

  public function store_tipo_organizacion(Request $request)
  {
    $request->validate([
      'nuevo_tipo_organizacion' => 'required|max:128',
    ]);

    try {
      $nuevo = new tipo_organizacion();
      $nuevo->nombre = $request->input('nuevo_tipo_organizacion');
      $nuevo->save();
      return redirect()->route('config.index')->with('message', $nuevo->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  public function store_linea_temporal(Request $request)
  {
    $request->validate([
      'nueva_linea_temporal' => 'required|max:128',
    ]);

    try {
      $nuevo = new lineas_temporales();
      $nuevo->nombre = $request->input('nueva_linea_temporal');
      $nuevo->save();
      return redirect()->route('config.index')->with('message', $nuevo->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(configuration $configuration)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_nombre_mundo(Request $request)
  {
    $request->validate([
      'nuevo_nombre_mundo' => 'required',
    ]);

    $nuevoNombre = $request->input('nuevo_nombre_mundo');

    $exito = Nombres::update_nombre_mundo($nuevoNombre);

    if ($exito) {
      return redirect()->route('config.index')->with('message', 'Nombre del mundo actualizado correctamente.');
    } else {
      return redirect()->route('config.index')->with('error', 'No se pudo guardar el nombre del mundo.');
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_fecha_mundo(Request $request)
  {
    $request->validate([
      'dia' => 'required|integer|min:1|max:30',
      'mes' => 'required',
      'anno' => 'required|integer',
    ]);

    $exito = Fecha::update_fecha_mundo($request->input('dia', 0), $request->input('mes', 0), $request->input('anno', 0));
    if ($exito) {
      return redirect()->route('config.index')->with('message', 'Fecha del mundo actualizada correctamente.');
    } else {
      return redirect()->route('config.index')->with('error', 'No se pudo actualizar la fecha del mundo.');
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request)
  {
    try {
      if ($request->tipo_editar == 'asentamiento') {
        $tipo_editar = tipo_asentamiento::findorfail($request->id_editar);
      }
      if ($request->tipo_editar == 'conflicto') {
        $tipo_editar = TipoConflicto::findorfail($request->id_editar);
      }
      if ($request->tipo_editar == 'construccion') {
        $tipo_editar = TipoConstruccion::findorfail($request->id_editar);
      }
      if ($request->tipo_editar == 'lugar') {
        $tipo_editar = tipo_lugar::findorfail($request->id_editar);
      }
      if ($request->tipo_editar == 'organizacion') {
        $tipo_editar = tipo_organizacion::findorfail($request->id_editar);
      }
      if ($request->tipo_editar == 'linea_temporal') {
        $tipo_editar = lineas_temporales::findorfail($request->id_editar);
      }
      $tipo_editar->nombre = $request->nombre_editar;
      $tipo_editar->save();
      return redirect()->route('config.index')->with('message', $tipo_editar->nombre . ' editado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(request $request)
  {
    try {
      if ($request->tipo == 'asentamiento') {
        tipo_asentamiento::destroy($request->id_borrar);
      }
      if ($request->tipo == 'conflicto') {
        TipoConflicto::destroy($request->id_borrar);
      }
      if ($request->tipo == 'construccion') {
        TipoConstruccion::destroy($request->id_borrar);
      }
      if ($request->tipo == 'lugar') {
        tipo_lugar::destroy($request->id_borrar);
      }
      if ($request->tipo == 'organizacion') {
        tipo_organizacion::destroy($request->id_borrar);
      }
      if ($request->tipo == 'linea_temporal') {
        lineas_temporales::destroy($request->id_borrar);
      }
      return redirect()->route('config.index')->with('message', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
    return redirect()->route('config.index')->with('error', 'No se pudo borrar.');
  }

  /**
   * Store the specified resource in storage.
   */
  public function store_fecha($dia = 0, $mes = 0, $anno = 0, $tabla)
  {
    try {
      //si los input de las fechas no se introducen, la fecha es indeterminada, no se guarda fecha
      if ($anno == 0 && $mes == 0 && $dia == 0) {
        $id_fecha = 0;
      } else {
        $fecha = new Fecha();
        $fecha->tabla = $tabla;
        $fecha->anno = $anno;
        $fecha->mes = $mes;
        $fecha->dia = $dia;
        $fecha->save();
        $id_fecha = DB::scalar("SELECT MAX(id) as id FROM fechas");
      }
      return $id_fecha;
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_fecha($dia = 0, $mes = 0, $anno = 0, $id)
  {
    try {
      $fecha = Fecha::findorfail($id);
      $fecha->anno = $anno;
      $fecha->mes = $mes;
      $fecha->dia = $dia;
      $fecha->save();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Se produjo un problema en la base de datos.');
    } catch (Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }
}
