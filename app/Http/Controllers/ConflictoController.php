<?php

namespace App\Http\Controllers;

use App\Models\Conflicto;
use App\Models\Fecha;
use App\Models\imagen;
use App\Models\organizacion;
use App\Models\personaje;
use App\Models\tipo_conflicto;
use App\Http\Controllers\ImagenController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConflictoController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index($orden = 'asc', $tipo = '0')
  {
    // Obtener todos los conflictos almacenados
    $conflictos = Conflicto::get_conflictos($tipo, $orden);

    // Obtener todos los tipos de conflictos almacenados
    $tipos = tipo_conflicto::get_tipos_conflictos();

    return view('conflictos.index', ['conflictos' => $conflictos, 'tipos' => $tipos, 'orden' => $orden, 'tipo_o' => $tipo]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    // Obtener todos los tipos de conflictos almacenados
    $tipos_conflicto = tipo_conflicto::get_tipos_conflictos();

    // Obtener todos los paises almacenados
    $paises = organizacion::get_organizaciones();

    // Obtener todos los conflictos almacenados, 0 para todos los tipos y asc para orden ascendente
    $conflictos = Conflicto::get_conflictos(0, 'asc');

    // Obtener todos los personajes almacenados
    $personajes = personaje::get_personajes();

    return view('conflictos.create', ['tipo_conflicto' => $tipos_conflicto, 'paises' => $paises, 'personajes' => $personajes, 'conflictos' => $conflictos]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'select_tipo' => 'required',
      'tipo_localizacion' => 'required',
      'dfin' => 'nullable|integer|min:1|max:30',
      'dinicio' => 'nullable|integer|min:1|max:30',
    ]);

    $conflicto = new Conflicto();

    $conflicto->nombre = $request->nombre;
    $conflicto->save();

    $id_conflicto = DB::scalar("SELECT MAX(id) as id FROM conflicto");
    if ($request->filled('descripcion')) {
      $conflicto->descripcion = app(ImagenController::class)->store_for_summernote($request->descripcion, "conflictos", $id_conflicto);
    }
    if ($request->filled('preludio')) {
      $conflicto->preludio = app(ImagenController::class)->store_for_summernote($request->preludio, "conflictos", $id_conflicto);
    }
    if ($request->filled('desarrollo')) {
      $conflicto->desarrollo = app(ImagenController::class)->store_for_summernote($request->desarrollo, "conflictos", $id_conflicto);
    }
    if ($request->filled('resultado')) {
      $conflicto->resultado = app(ImagenController::class)->store_for_summernote($request->resultado, "conflictos", $id_conflicto);
    }
    if ($request->filled('consecuencias')) {
      $conflicto->consecuencias = app(ImagenController::class)->store_for_summernote($request->consecuencias, "conflictos", $id_conflicto);
    }
    if ($request->filled('otros')) {
      $conflicto->otros = app(ImagenController::class)->store_for_summernote($request->otros, "conflictos", $id_conflicto);
    }

    if ($request->filled('atacantesp')) {
      $atacantes = $request->input('atacantesp');
      try {
        foreach ($atacantes as $atacante) {
          DB::table('conflicto_personajes')->insert([
            'id_conflicto' => $id_conflicto,
            'id_personaje' => $atacante,
            'rol' => 'atacante'
          ]);
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
      } catch (Exception $excepcion) {
      }
    }

    if ($request->filled('defensoresp')) {
      $defensores = $request->input('defensoresp');
      try {
        foreach ($defensores as $defensor) {
          DB::table('conflicto_personajes')->insert([
            'id_conflicto' => $id_conflicto,
            'id_personaje' => $defensor,
            'rol' => 'defensor'
          ]);
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
      } catch (Exception $excepcion) {
      }
    }

    if ($request->filled('atacantes')) {
      $atacantes = $request->input('atacantes');
      try {
        foreach ($atacantes as $atacante) {
          DB::table('conflicto_beligerantes')->insert([
            'id_conflicto' => $id_conflicto,
            'id_organizacion' => $atacante,
            'lado' => 'atacante'
          ]);
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
      } catch (Exception $excepcion) {
      }
    }

    if ($request->filled('defensores')) {
      $defensores = $request->input('defensores');
      try {
        foreach ($defensores as $defensor) {
          DB::table('conflicto_beligerantes')->insert([
            'id_conflicto' => $id_conflicto,
            'id_organizacion' => $defensor,
            'lado' => 'defensor'
          ]);
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
      } catch (Exception $excepcion) {
      }
    }

    $conflicto->tipo_localizacion = $request->input('tipo_localizacion');
    $conflicto->id_tipo_conflicto = $request->select_tipo;
    $conflicto->id_conflicto_padre = $request->select_padre;

    try {
      //------------fechas----------//
      $conflicto->fecha_inicio = app(ConfigurationController::class)->store_fecha($request->input('dinicio', 0), $request->input('minicio', 0), $request->input('ainicio', 0), "conflictos");
      $conflicto->fecha_fin = app(ConfigurationController::class)->store_fecha($request->input('dfin', 0), $request->input('mfin', 0), $request->input('afin', 0), "conflictos");

      $conflicto->save();
      return redirect()->route('conflictos.index')->with('message', 'Conflicto ' . $conflicto->nombre . ' añadido correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('ConflictoController->store: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('conflictos.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo añadir.');
    } catch (Exception $excepcion) {
      Log::error('ConflictoController->store: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('conflictos.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Conflicto $conflicto)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    // Obtener el conflicto a editar
    $conflicto = Conflicto::get_conflicto($id);
    if ($conflicto['error'] ?? false) {
      return redirect()->route('conflictos.index')->with('error', $conflicto['error']['error']);
    }

    $fecha_inicio = 0;
    $fecha_fin = 0;

    // Obtener todos los tipos de conflictos almacenados
    $tipos_conflicto = tipo_conflicto::get_tipos_conflictos();

    // Obtener todos los paises almacenados
    $paises = organizacion::get_organizaciones();

    // Obtener todos los personajes almacenados
    $personajes = personaje::get_personajes_id_nombre();

    // Obtener todos los conflictos almacenados, 0 para todos los tipos y asc para orden ascendente
    $conflictos = Conflicto::get_conflictos(0, 'asc');

    //Obtener los atacantes y defensores del conflicto
    $atacantes=Conflicto::get_paises_bando($id,'atacante');
    $defensores=Conflicto::get_paises_bando($id,'defensor');

    //Obtener los atacantes y defensores del conflicto
    $atacantesp=Conflicto::get_personajes_bando($id,'atacante');
    $defensoresp=Conflicto::get_personajes_bando($id,'defensor');

    if ($conflicto->fecha_inicio != 0) {
      $fecha_inicio = Fecha::find($conflicto->fecha_inicio);
    } else {
      $fecha_inicio = Fecha::find(0);
    }

    if ($conflicto->fecha_fin != 0) {
      $fecha_fin = Fecha::find($conflicto->fecha_fin);
    } else {
      $fecha_fin = Fecha::find(0);
    }

    return view('conflictos.edit', ['conflicto' => $conflicto, 'inicio' => $fecha_inicio, 'fin' => $fecha_fin, 'tipo_conflicto' => $tipos_conflicto, 'personajes' => $personajes, 'conflictos' => $conflictos, 'paises' => $paises, 'atacantes' => $atacantes, 'defensores' => $defensores, 'atacantesp' => $atacantesp, 'defensoresp' => $defensoresp]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Conflicto $conflicto)
  {
    $request->validate([
      'nombre' => 'required|max:256',
      'select_tipo' => 'required',
      'tipo_localizacion' => 'required',
      'dfin' => 'nullable|integer|min:1|max:30',
      'dinicio' => 'nullable|integer|min:1|max:30',
    ]);

    // Obtener el conflicto a editar
    $conflicto = Conflicto::get_conflicto($request->id);
    if ($conflicto['error'] ?? false) {
      return redirect()->route('conflictos.index')->with('error', $conflicto['error']['error']);
    }

    $conflicto->nombre = $request->nombre;
    $conflicto->tipo_localizacion = $request->input('tipo_localizacion');
    $conflicto->id_tipo_conflicto = $request->select_tipo;
    $conflicto->id_conflicto_padre = $request->select_padre;

    if ($request->filled('descripcion')) {
      $conflicto->descripcion = app(ImagenController::class)->update_for_summernote($request->descripcion, "conflictos", $request->id);
    }
    if ($request->filled('preludio')) {
      $conflicto->preludio = app(ImagenController::class)->update_for_summernote($request->preludio, "conflictos", $request->id);
    }
    if ($request->filled('desarrollo')) {
      $conflicto->desarrollo = app(ImagenController::class)->update_for_summernote($request->desarrollo, "conflictos", $request->id);
    }
    if ($request->filled('resultado')) {
      $conflicto->resultado = app(ImagenController::class)->update_for_summernote($request->resultado, "conflictos", $request->id);
    }
    if ($request->filled('consecuencias')) {
      $conflicto->consecuencias = app(ImagenController::class)->update_for_summernote($request->consecuencias, "conflictos", $request->id);
    }
    if ($request->filled('otros')) {
      $conflicto->otros = app(ImagenController::class)->update_for_summernote($request->otros, "conflictos", $request->id);
    }

    //para actualizar los atacantes y defensores, se borran todos los antiguos y se vuelven a añadir
    if ($request->filled('atacantes')) {
      DB::table('conflicto_beligerantes')->where('lado', '=', 'atacante')->where('id_conflicto', '=', $request->id)->delete();
      $atacantes = $request->input('atacantes');
      try {
        foreach ($atacantes as $atacante) {
          DB::table('conflicto_beligerantes')->insert([
            'id_conflicto' => $request->id,
            'id_organizacion' => $atacante,
            'lado' => 'atacante'
          ]);
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
      } catch (Exception $excepcion) {
      }
    }

    if ($request->filled('defensores')) {
      DB::table('conflicto_beligerantes')->where('lado', '=', 'defensor')->where('id_conflicto', '=', $request->id)->delete();
      $defensores = $request->input('defensores');
      try {
        foreach ($defensores as $defensor) {
          DB::table('conflicto_beligerantes')->insert([
            'id_conflicto' => $request->id,
            'id_organizacion' => $defensor,
            'lado' => 'defensor'
          ]);
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
      } catch (Exception $excepcion) {
      }
    }

    //para actualizar los personajes atacantes y defensores, se borran todos los antiguos y se vuelven a añadir
    if ($request->filled('atacantesp')) {
      DB::table('conflicto_personajes')->where('rol', '=', 'atacante')->where('id_conflicto', '=', $request->id)->delete();
      $atacantes = $request->input('atacantesp');
      try {
        foreach ($atacantes as $atacante) {
          DB::table('conflicto_personajes')->insert([
            'id_conflicto' => $request->id,
            'id_personaje' => $atacante,
            'rol' => 'atacante'
          ]);
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
      } catch (Exception $excepcion) {
      }
    }

    if ($request->filled('defensoresp')) {
      DB::table('conflicto_personajes')->where('rol', '=', 'defensor')->where('id_conflicto', '=', $request->id)->delete();
      $defensores = $request->input('defensoresp');
      try {
        foreach ($defensores as $defensor) {
          DB::table('conflicto_personajes')->insert([
            'id_conflicto' => $request->id,
            'id_personaje' => $defensor,
            'rol' => 'defensor'
          ]);
        }
      } catch (\Illuminate\Database\QueryException $excepcion) {
      } catch (Exception $excepcion) {
      }
    }

    try {
      //------------fechas----------//
      if ($request->input('ainicio', 0) != 0) {
        if ($conflicto->fecha_inicio != 0) {
          //el conflicto ya tenía fecha de inicio antes de editar
          app(ConfigurationController::class)->update_fecha($request->input('dinicio', 0), $request->input('minicio', 0), $request->input('ainicio', 0), $conflicto->fecha_inicio);
        } else {
          //el conflicto no tenía fecha de inicio antes de editar, hay que añadirla a la db.
          $conflicto->fecha_inicio = app(ConfigurationController::class)->store_fecha($request->input('dinicio', 0), $request->input('minicio', 0), $request->input('ainicio', 0), "conflictos");
        }
      }

      if ($request->input('afin', 0) != 0) {
        if ($conflicto->fecha_fin != 0) {
          //el conflicto ya tenía fecha de fin antes de editar
          app(ConfigurationController::class)->update_fecha($request->input('dfin', 0), $request->input('mfin', 0), $request->input('afin', 0), $conflicto->fecha_fin);
        } else {
          //el conflicto no tenía fecha de fin antes de editar, hay que añadirla a la db.
          $conflicto->fecha_fin = app(ConfigurationController::class)->store_fecha($request->input('dfin', 0), $request->input('mfin', 0), $request->input('afin', 0), "conflictos");
        }
      }

      $conflicto->save();
      return redirect()->route('conflictos.index')->with('message', 'conflicto ' . $conflicto->nombre . ' editado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('ConflictoController->update: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('conflictos.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo editar.');
    } catch (Exception $excepcion) {
      Log::error('ConflictoController->update: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('conflictos.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Request $request)
  {
    try {
      $fecha_inicio = DB::scalar("SELECT fecha_inicio FROM conflicto where id = ?", [$request->id_borrar]);
      $fecha_fin = DB::scalar("SELECT fecha_fin FROM conflicto where id = ?", [$request->id_borrar]);

      //si fecha inicio/fin != 0, la conflicto tiene fecha establecida, hay que borrar
      if ($fecha_inicio != 0) {
        Fecha::destroy($fecha_inicio);
      }
      if ($fecha_fin != 0) {
        Fecha::destroy($fecha_fin);
      }

      //borrado de las imagenes que pueda haber de summernote
      $imagenes = DB::table('imagenes')
        ->select('id', 'nombre')
        ->where('table_owner', '=', 'conflictos')
        ->where('owner', '=', $request->id_borrar)->get();

      foreach ($imagenes as $imagen) {
        if (file_exists(public_path("/storage/imagenes/" . $imagen->nombre))) {
          unlink(public_path("/storage/imagenes/" . $imagen->nombre));
          //Storage::delete(asset($imagen->nombre));
        }
        imagen::destroy($imagen->id);
      }
      //borrado de los registros que pueda haber de beligerantes (atacantes y defensores)
      DB::table('conflicto_beligerantes')->where('id_conflicto', '=', $request->id_borrar)->delete();

      //borrado de los registros que pueda haber de personajes (atacantes y defensores)
      DB::table('conflicto_personajes')->where('id_conflicto', '=', $request->id_borrar)->delete();

      Conflicto::destroy($request->id_borrar);
      return redirect()->route('conflictos.index')->with('message', $request->nombre_borrado . ' borrado correctamente.');
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('ConflictoController->destroy: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('conflictos.index')->with('error', 'Se produjo un problema en la base de datos, no se pudo borrar.');
    } catch (Exception $excepcion) {
      Log::error('ConflictoController->destroy: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      return redirect()->route('conflictos.index')->with('error', $excepcion->getMessage());
    }
  }

  /**
   * Display a listing of the resource searched.
   */
  public function search(Request $request)
  {
    $search = $request->input('search');
    try {
      $conflictos = DB::table('conflicto')
        ->leftjoin('tipo_conflicto', 'conflicto.id_tipo_conflicto', '=', 'tipo_conflicto.id')
        ->select('conflicto.id', 'conflicto.nombre', 'descripcion', 'tipo_conflicto.nombre AS tipo')
        ->where('conflicto.nombre', 'LIKE', "%{$search}%")
        ->orderBy('nombre', 'asc')->get();
    } catch (\Illuminate\Database\QueryException $excepcion) {
      Log::error('ConflictoController->search: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $conflictos = ['error' => ['error' => 'Se produjo un problema en la base de datos.']];
    } catch (Exception $excepcion) {
      Log::error('ConflictoController->search: Se produjo un problema en la base de datos.: ' . $excepcion->getMessage());
      $conflictos = ['error' => ['error' => $excepcion->getMessage()]];
    }

    // Obtener todos los tipos de conflictos almacenados
    $tipos = tipo_conflicto::get_tipos_conflictos();

    return view('conflictos.index', ['conflictos' => $conflictos, 'tipos' => $tipos, 'orden' => 'asc', 'tipo_o' => 0]);
  }
}
