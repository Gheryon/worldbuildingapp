<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use App\Http\Requests\EventoRequest;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventosController extends Controller
{
  /**
   * Obtiene y muestra una lista paginada de todos los eventos del sistema.
   * 
   * Une diferentes tipos de eventos (eventos principales, nacimientos,
   * defunciones, conflictos, fundaciones, etc.) en una única lista ordenada
   * cronológicamente y los muestra en la vista de timelines.
   * 
   * @param Request $request Objeto de solicitud HTTP que puede contener el parámetro
   *                         'orden' para especificar si el orden es 'asc' o 'desc'.
   *                         Por defecto es 'desc'.
   * 
   * @return \Illuminate\View\View Vista que muestra la lista paginada de eventos.
   */
  public function index(Request $request)
  {
    $orden = $request->get('orden', 'desc');

    $filtros = [
      'desde_anno' => $request->get('desde_anno'),
      'hasta_anno' => $request->get('hasta_anno'),
      'tipo'       => $request->get('tipo'),
      'categoria'  => $request->get('categoria'),
    ];

    // Obtener todas las consultas de eventos unificadas y ordenadas cronológicamente
    $eventos = $this->obtenerEventosUnificados($orden, $filtros);

    return view('timelines.index', compact('eventos', 'orden', 'filtros'));
  }

  /**
   * Unifica todas las consultas de eventos en una única colección paginada.
   * 
   * Combina diferentes tipos de eventos (eventos principales, nacimientos,
   * defunciones, conflictos, asentamientos, organizaciones) y los ordena según
   * el criterio especificado.
   * 
   * @param string $orden Criterio de ordenamiento ('asc' o 'desc') para los campos
   *                      de fecha (año, mes, día).
   * 
   * @return \Illuminate\Pagination\LengthAwarePaginator Colección paginada de eventos
   *                                                     unificados y ordenados.
   */
  private function obtenerEventosUnificados(string $orden, array $filtros)
  {
    //Mapa de todas las posibles consultas de tablas externas para convertir en eventos
    $mapaQueries = [
        'personajes'     => [
            'nac' => $this->construirQueryEvento('personajes', 'nacimiento_id', 'nacimiento','personajes.nombre', 'personajes.descripcion_corta', $filtros),
            'def' => $this->construirQueryEvento('personajes', 'fallecimiento_id', 'defuncion', 'personajes.nombre', 'personajes.causa_fallecimiento', $filtros)
        ],
        'conflictos'     => [
            'ini' => $this->construirQueryEvento('conflictos', 'fecha_inicio_id', 'ini_conflicto', 'conflictos.nombre', 'conflictos.descripcion', $filtros),
            'fin' => $this->construirQueryEvento('conflictos', 'fecha_fin_id', 'fin_conflicto', 'conflictos.nombre', 'conflictos.consecuencias', $filtros)
        ],
        'asentamientos'  => [
            'fund' => $this->construirQueryEvento('asentamientos', 'fundacion_id', 'asentamiento', "CONCAT('Fundación de ', asentamientos.nombre)", 'asentamientos.descripcion', $filtros),
            'destr' => $this->construirQueryEvento('asentamientos', 'disolucion_id', 'asentamiento', "CONCAT('Destrucción de ', asentamientos.nombre)", 'asentamientos.descripcion', $filtros)
        ],
        'organizaciones'  => [
            'fund' => $this->construirQueryEvento('organizaciones', 'fundacion_id', 'organizacion', "CONCAT('Fundación de ', organizaciones.nombre)", 'organizaciones.descripcion_breve', $filtros),
            'disol' => $this->construirQueryEvento('organizaciones', 'disolucion_id', 'organizacion', "CONCAT('Disolución de ', organizaciones.nombre)", 'organizaciones.descripcion_breve', $filtros)
        ],
        /*'religiones'  => [
            'fund' => $this->construirQueryEvento('religiones', 'fundacion_id', 'religion', "CONCAT('Fundación de ', religiones.nombre)", 'religiones.descripcion', $filtros),
            'destr' => $this->construirQueryEvento('religiones', 'disolucion_id', 'religion', "CONCAT('Desaparición de ', religiones.nombre)", 'religiones.descripcion', $filtros)
        ],*/
    ];

    //Determinar qué queries de datos incluir
    $queriesAUnir = [];
    $tipoFiltro = $filtros['tipo'] ?? null;

    // Añadir las entidades externas si coinciden con el filtro de tipo o si no hay filtro
    foreach ($mapaQueries as $tabla => $subqueries) {
        if (!$tipoFiltro || $tipoFiltro === $tabla) {
            foreach ($subqueries as $q) $queriesAUnir[] = $q;
        }
    }

    // --- Manejo especifico de la tabla 'eventos' ---
    // Si no hay filtro de tipo, o el filtro NO es una de las tablas externas,
    // se asume que el usuario busca un tipo dentro de la tabla 'eventos' (general, crisis, etc.)
    $esTipoEventoPropio = !array_key_exists($tipoFiltro, $mapaQueries);

    if (!$tipoFiltro || $esTipoEventoPropio) {
        $queriesAUnir[] = $this->construirQueryEvento(
            'eventos', 
            'fecha_id', 
            $tipoFiltro ?? 0, // Si no hay filtro, por defecto 'general'
            'eventos.nombre', 
            'eventos.descripcion', 
            $filtros // Aquí ya se aplica el where('tipo', $tipo) dentro de construirQueryEvento
        );
    }
    
    //$hayFiltroActivo = !empty($filtros['tipo']) || !empty($filtros['categoria']);
    //añadir eventos universales SIEMPRE, aunque el filtro de tipo o categoria no los incluya (ej: si el usuario filtra por nacimientos, también se mostrarán eventos universales)
    if ($tipoFiltro && !$esTipoEventoPropio) {
        $filtrosUni = $filtros;
        $filtrosUni['categoria'] = 'universal';
        unset($filtrosUni['tipo']); // Limpiamos el tipo para que traiga todos los universales
        $queriesAUnir[] = $this->construirQueryEvento('eventos', 'fecha_id', 'general', 'eventos.nombre', 'eventos.descripcion', $filtrosUni);
    }

    //Añadir SIEMPRE la fecha actual al array de queries a unir
    // (A menos que el usuario esté filtrando por un rango de fechas donde hoy no entre)
    $queriesAUnir[] = $this->obtenerFechaActualComoEvento();

    if (empty($queriesAUnir)) {
      // Retornar query vacía o manejar error si no hay nada
      return DB::table('eventos')->whereRaw('1 = 0')->paginate(200);
    }
    //Ejecutamos la unión
    $queryPrincipal = array_shift($queriesAUnir);
    foreach ($queriesAUnir as $query) {
      $queryPrincipal->unionAll($query);
    }

    return $queryPrincipal
      ->orderBy('anno', $orden)
      ->orderBy('mes', $orden)
      ->orderBy('dia', $orden)
      ->paginate(200)
      ->withQueryString();
  }

  /**
   * Genera una consulta estandarizada para ser utilizada en un unionAll.
   */
  private function construirQueryEvento(
    string $tabla,
    string $columnaFecha,
    string $tipoEvento,
    string $nombreRaw,
    string $descripcionCol,
    array $filtros = []
  ) {
    $query = DB::table($tabla)
      ->join('fechas', "$tabla.$columnaFecha", '=', 'fechas.id')
      ->select(
        DB::raw(("$tabla.id") . " as id"),
        DB::raw("$nombreRaw as nombre"),
        DB::raw("$descripcionCol as descripcion"),
        "$tabla.$columnaFecha as fecha_id",
        $tipoEvento ? DB::raw("'$tipoEvento' as tipo") : 'eventos.tipo',
        'fechas.anno',
        'fechas.mes',
        'fechas.dia',
        "$tabla.categoria"
      )
      ->whereNotNull("$tabla.$columnaFecha");

    // Si la tabla es 'eventos' y tenemos un filtro de tipo, lo aplicamos
    if ($tabla === 'eventos' && !empty($filtros['tipo'])) {
        $query->where('eventos.tipo', '=', $filtros['tipo']);
    }

    // --- Lógica de filtrado por rango cronológico ---
    // Filtro desde (Fecha 1)
    if (!empty($filtros['desde_anno'])) {
      $query->where('fechas.anno', '>=', $filtros['desde_anno']);
    }

    // Filtro hasta (Fecha 2)
    if (!empty($filtros['hasta_anno'])) {
      $query->where('fechas.anno', '<=', $filtros['hasta_anno']);
    }

    // --- Lógica de filtrado por categoría ---
    if (!empty($filtros['categoria'])) {
      $query->where('categoria', '=', $filtros['categoria']);
    }

    return $query;
  }

  /**
   * Crea un registro ficticio para representar la fecha actual del mundo como un evento.
   * 
   * Este método genera un evento especial que representa el día presente, utilizando
   * los datos de la fecha con ID 1 de la tabla de fechas.
   * 
   * @return \Illuminate\Database\QueryBuilder Consulta preparada para obtener
   *                                           la fecha actual como evento.
   */
  private function obtenerFechaActualComoEvento()
  {
    return DB::table('fechas')
      ->where('id', 1)
      ->select(
        DB::raw('0 as id'),
        DB::raw("'Fecha Actual del Mundo' as nombre"),
        DB::raw("'Día presente.' as descripcion"),
        'id as fecha_id',
        DB::raw("'fecha_actual' as tipo"),
        'anno',
        'mes',
        'dia',
        DB::raw("'fecha_actual' as categoria")
      );
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //se hace desde modal en el index
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(EventoRequest $request)
  {
    $datosValidados = $request->validated();
    try {
      // Llamada a la lógica del modelo
      $evento = Evento::store_evento($datosValidados);

      return redirect()->route('timelines.index')
        ->with('success', 'Evento ' . $evento->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al añadir evento.",
        [
          'entrada_input' => $request->validated(),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el evento debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al añadir evento.",
        [
          'entrada_input' => $request->validated(),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear el evento: ' . $e->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show($timeline)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    // Cargar el evento con su relación de fecha
    $evento = Evento::with('fecha')->find($id);

    if (!$evento) {
        return response()->json(['error' => 'Evento no encontrado'], 404);
    }

    return response()->json([
        'id'          => $evento->id,
        'nombre'      => $evento->nombre,
        'descripcion' => $evento->descripcion,
        'tipo'        => $evento->tipo,
        'categoria'   => $evento->categoria,
        'dia'         => $evento->fecha->dia ?? '',
        'mes'         => $evento->fecha->mes ?? '',
        'anno'        => $evento->fecha->anno ?? '',
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(EventoRequest $request, $id)
  {
    $datosValidados = $request->validated();
    try {
      // Llamada a la lógica del modelo
      $evento=Evento::findOrFail($id);
      $evento->update_evento($datosValidados);

      return redirect()->route('timelines.index')
        ->with('success', 'Evento ' . $evento->nombre . ' editado correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error(
        "Error de base de datos al editar evento.",
        [
          'entrada_input' => $request->validated(),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo editar el evento debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical(
        "Error inesperado al editar evento.",
        [
          'entrada_input' => $request->validated(),
          'error' => $e->getMessage(),
          'exception' => $e,
        ]
      );
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo editar el evento: ' . $e->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    try {
      $evento = Evento::findOrFail($request->id_borrar);

      DB::transaction(function () use ($evento) {
        $evento->delete();
      });

      return redirect()->route('timelines.index')
        ->with('success', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Exception $e) {
      Log::error('Error al borrar conflicto: ' . $e->getMessage());
      return redirect()->route('timelines.index')
        ->with('error', 'No se pudo borrar el conflicto.');
    }
  }
}
