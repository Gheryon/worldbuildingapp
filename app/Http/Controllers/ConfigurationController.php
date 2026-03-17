<?php

namespace App\Http\Controllers;

use App\Models\Nombres;
use App\Models\lineas_temporales;
use App\Models\Fecha;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class ConfigurationController extends Controller
{
  //Mapa de modelos
  private $modelMap = [
    'asentamiento'   => \App\Models\TipoAsentamiento::class,
    'conflicto'      => \App\Models\TipoConflicto::class,
    'construccion'   => \App\Models\TipoConstruccion::class,
    'lugar'          => \App\Models\TipoLugar::class,
    'organizacion'   => \App\Models\TipoOrganizacion::class,
  ];

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $data = [
      'Nombre_mundo' => Nombres::get_nombre_mundo(), //
      'fecha'        => Fecha::get_fecha_mundo(),  //
    ];

    // Mapeo para automatizar la carga
    $catalogos = [
      'tipos_asentamiento'    => \App\Models\TipoAsentamiento::class,
      'tipos_conflicto'       => \App\Models\TipoConflicto::class,
      'tipos_construccion'    => \App\Models\TipoConstruccion::class,
      'tipos_lugar'           => \App\Models\TipoLugar::class,
      'tipos_organizaciones'  => \App\Models\TipoOrganizacion::class,
    ];

    foreach ($catalogos as $key => $modelClass) {
      try {
        // Asumimos que todos tienen el método ordenado o usamos Eloquent directo
        $data[$key] = $modelClass::orderBy('nombre', 'asc')->get();
      } catch (Exception $e) {
        Log::error("Error cargando $key: " . $e->getMessage());
        $data[$key] = collect(); // Devolvemos colección vacía para no romper el @foreach en la vista
      }
    }

    return view('config.index', $data);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage (Generic version).
   */
  public function store(Request $request, $type)
  {
    //Verificar si el tipo existe en el mapa
    if (!isset($this->modelMap[$type])) {
      return redirect()->route('config.index')->with('error', 'Tipo de configuración no válido.');
    }

    //Validar dinámicamente según el nombre del input que envía el componente
    $inputName = "nuevo_tipo_{$type}";
    $request->validate([
      $inputName => 'required|string|max:128',
    ]);

    try {
      $modelClass = $this->modelMap[$type];

      // 3. Crear el registro usando Mass Assignment (requiere $fillable en el modelo)
      $nuevo = $modelClass::create([
        'nombre' => $request->input($inputName)
      ]);

      return redirect()->route('config.index')
        ->with('message', "{$nuevo->nombre} añadido correctamente a {$type}.");
    } catch (\Illuminate\Database\QueryException $excepcion) {
      return redirect()->route('config.index')->with('error', 'Error de base de datos al guardar.');
    } catch (\Exception $excepcion) {
      return redirect()->route('config.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Update the specified resource in storage (Generic version).
   */
  public function update(Request $request)
  {
    $request->validate([
      'id_editar'     => 'required|integer',
      'tipo_editar'   => 'required|string',
      'nombre_editar' => 'required|string|max:128',
    ]);

    // Verificar si el tipo existe en el mapa de modelos
    if (!isset($this->modelMap[$request->tipo_editar])) {
      return redirect()->route('config.index')->with('error', 'Tipo de configuración no válido.');
    }

    try {
      $modelClass = $this->modelMap[$request->tipo_editar];

      // Buscar el registro
      $registro = $modelClass::findOrFail($request->id_editar);

      //Actualizar y guardar
      $registro->nombre = $request->nombre_editar;
      $registro->save();

      return redirect()->route('config.index')
        ->with('message', "{$registro->nombre} actualizado correctamente.");
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return redirect()->route('config.index')->with('error', 'El registro no existe.');
    } catch (\Exception $e) {
      return redirect()->route('config.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage (Generic version).
   */
  public function destroy(Request $request)
  {
    $request->validate([
      'id_borrar' => 'required|integer',
      'tipo'      => 'required|string',
    ]);

    //Comprobar si el tipo es válido en nuestro mapa
    if (!isset($this->modelMap[$request->tipo])) {
      return redirect()->route('config.index')->with('error', 'Tipo de entidad no válido.');
    }

    try {
      $modelClass = $this->modelMap[$request->tipo];

      $registro = $modelClass::findOrFail($request->id_borrar);
      $nombreGuardado = $registro->nombre; // Para el mensaje de confirmación
      $registro->delete();

      return redirect()->route('config.index')
        ->with('message', "El registro '{$nombreGuardado}' ha sido eliminado correctamente.");
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      return redirect()->route('config.index')->with('error', 'El registro ya no existe.');
    } catch (\Exception $e) {
      return redirect()->route('config.index')->with('error', 'Error al eliminar: ' . $e->getMessage());
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_nombre_mundo(Request $request)
  {
    $request->validate([
      'nuevo_nombre_mundo' => 'required|string|max:255'
    ]);

    try {
      $nuevoNombre = $request->input('nuevo_nombre_mundo');

      $exito = Nombres::update_nombre_mundo($nuevoNombre);

      return redirect()->route('config.index')
        ->with('message', 'Nombre del mundo actualizado con éxito.');
    } catch (\Exception $e) {
      Log::error("Error al actualizar nombre mundo: " . $e->getMessage());
      return redirect()->route('config.index')->with('error', 'No se pudo actualizar el nombre.');
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update_fecha_mundo(Request $request)
  {
    $request->validate([
        'dia'  => 'required|integer|min:1|max:30',
        'mes'  => 'required|integer|min:0|max:12',
        'anno' => 'required|integer',
    ]);

    $exito = Fecha::update_fecha_mundo($request->input('dia', 0), $request->input('mes', 0), $request->input('anno', 0));
    if ($exito) {
      return redirect()->route('config.index')->with('message', 'Fecha del mundo actualizada correctamente.');
    } else {
      return redirect()->route('config.index')->with('error', 'No se pudo actualizar la fecha del mundo.');
    }
  }
}
