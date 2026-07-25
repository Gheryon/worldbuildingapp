<?php

namespace App\Http\Controllers;

use App\Models\Cultura;
use App\Models\Fecha;
use Illuminate\Http\Request;
use App\Http\Requests\CulturaRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CulturasController extends Controller
{
  public function index(Request $request)
  {
    $datosValidados = $request->validate([
      'orden' => 'sometimes|string|in:asc,desc',
      'estatus' => 'sometimes|nullable|string|max:20',
      'search' => 'sometimes|nullable|string|max:100',
    ], [
      'orden.in' => 'El orden debe ser ascendente (asc) o descendente (desc).',
    ]);

    $orden = $datosValidados['orden'] ?? 'asc';
    $estatus_id = $datosValidados['estatus'] ?? '0';
    $terminoBusqueda = $datosValidados['search'] ?? null;

    $culturas = Cultura::filtrar([
      'orden' => $orden,
      'estatus' => $estatus_id,
      'search' => $terminoBusqueda,
    ])->paginate(18);

    return view('culturas.index', compact('culturas', 'orden', 'estatus_id', 'terminoBusqueda'));
  }

  public function create()
  {
    $culturas = Cultura::orderBy('nombre', 'asc')->pluck('nombre', 'id');

    return view('culturas.create', compact('culturas'));
  }

  public function store(CulturaRequest $request)
  {
    $datosValidados = $request->validated();

    try {
      $cultura = Cultura::store_cultura($datosValidados);

      return redirect()->route('culturas.index')
        ->with('success', 'Cultura ' . $cultura->nombre . ' añadida correctamente.');
    } catch (\Illuminate\Database\QueryException $e) {
      Log::error("Error de base de datos al añadir cultura.", [
        'entrada_input' => $request,
        'error' => $e->getMessage(),
        'exception' => $e,
      ]);
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear la cultura debido a un error en la base de datos.');
    } catch (\Exception $e) {
      Log::critical("Error inesperado al añadir cultura.", [
        'entrada_input' => $request,
        'error' => $e->getMessage(),
        'exception' => $e,
      ]);
      return redirect()->back()
        ->withInput()
        ->with('error', 'No se pudo crear la cultura: ' . $e->getMessage());
    }
  }

  public function show($id)
  {
    try {
      $cultura = Cultura::with([
        'cultura_madre',
        'imagenes',
        'culturas_hijas' => function ($query) {
          $query->orderBy('nombre', 'asc');
        }
      ])->findOrFail($id);

      $fundacion = Fecha::get_fecha_string($cultura->fundacion_id);
      $disolucion = Fecha::get_fecha_string($cultura->disolucion_id);

      return view('culturas.show', compact('cultura', 'fundacion', 'disolucion'));
    } catch (\Exception $e) {
      Log::error("Error al mostrar cultura: " . $e->getMessage());
      return redirect()->route('culturas.index')
        ->with('error', 'Cultura no encontrada.');
    }
  }

  public function edit($id)
  {
    try {
      $cultura = Cultura::with(['fecha_fundacion', 'fecha_disolucion', 'cultura_madre'])->findOrFail($id);

      $culturas = Cultura::orderBy('nombre', 'asc')->pluck('nombre', 'id');

      return view('culturas.edit', compact('cultura', 'culturas'));
    } catch (\Exception $e) {
      return redirect()->route('culturas.index')
        ->with('error', 'No se pudo cargar la cultura: ' . $e->getMessage());
    }
  }

  public function update(CulturaRequest $request, Cultura $cultura)
  {
    $datosValidados = $request->validated();

    try {
      $cultura->update_cultura($datosValidados);

      return redirect()->route('culturas.index')
        ->with('success', 'Cultura ' . $cultura->nombre . ' actualizada con éxito.');
    } catch (\Exception $e) {
      Log::error("Error actualizando cultura ID {$cultura->id}: " . $e->getMessage());
      return redirect()->back()
        ->withInput()
        ->with('error', 'Error al actualizar: ' . $e->getMessage());
    }
  }

  public function destroy(Cultura $cultura)
  {
    $nombre = $cultura->nombre;

    try {
      DB::transaction(function () use ($cultura) {
        $cultura->delete();
      });

      return redirect()->route('culturas.index')
        ->with('success', 'La cultura ' . $nombre . ' ha sido borrada correctamente.');
    } catch (\Exception $e) {
      Log::error("Error al eliminar cultura ID {$cultura->id}: " . $e->getMessage());
      return redirect()->route('culturas.index')
        ->with('error', 'No se pudo borrar la cultura.');
    }
  }
}
