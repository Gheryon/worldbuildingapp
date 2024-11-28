@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Configuración</title>
@endsection

@section('navbar-buttons')
@endsection

@section('content')
<div class="row">
  <h1>Configuración</h1>
</div>
<hr>

<!-- Modal -->
<div class="modal fade" id="confirmar_eliminacion" tabindex="-1" role="dialog" aria-labelledby="Confirmar eliminacion" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-danger">
        <div class="card-header">
          <h5 class="card-title" id="confirmar_eliminacion">Confirmar eliminación</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <div class="row">
            <p> ¿Borrar tipo: <span id="texto-borrar"> </span>?</p>
          </div>
        </div>
        <div class="card-footer">
          <form id="form-confirmar-borrar" class="col-md-auto" action="{{route('config.destroy')}}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id_borrar" id="id_borrar">
            <input type="hidden" name="tipo" id="tipo">
            <input type="hidden" name="nombre_borrado" id="nombre_borrado">
            <button type="button" id="cancelar-borrar-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="confirmar-borrar-button" class="btn btn-danger">Eliminar</button>
            <button type="button" id="cerrar-borrar-button" class="btn btn-primary" data-dismiss="modal" style="display:none">Cerrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editar_nombre" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="Editar nombre" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-dark">
        <div class="card-header">
          <h5 class="card-title" id="nuevoEventoLabel">Editar</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <form id="form-editar-nombre" class="col-md-auto" action="{{route('config.update')}}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_editar" id="id_editar">
            <input type="hidden" name="tipo_editar" id="tipo_editar">
            <div class="row">
              <div class="col">
                <label for="nombre_editar" class="form-label">Nombre</label>
                <input type="text" name="nombre_editar" class="form-control" id="nombre_editar" required>
                <div class="invalid-feedback">
                  Nombre no puede estar vacío.
                </div>
              </div>
            </div>
        </div>
        <div class="card-footer">
          <button type="button" id="cancelar-editar-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
          <button type="submit" id="submit-editar-button" class="btn btn-success">Guardar</button>
          <button type="button" id="cerrar-editar-button" class="btn btn-primary" data-dismiss="modal" style="display:none">Cerrar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="col-md-12">
  <!--<div class="row">
    <button id="back_up" class="btn btn-primary backup_button">Copia de seguridad</button>
  </div>-->
  
  <div class="row">
    <div class="col-4">
    <form id="form-edit-nombre_mundo" class="form-inline" action="{{route('config.update_nombre_mundo')}}" method="POST">
      @csrf
      <div class="form-group mb-2">
        <label for="nombre_mundo" class="sr-only">Nombre del mundo</label>
        <input type="text" readonly class="form-control-plaintext" id="nombre_mundo" value="Nombre del mundo">
      </div>
      <div class="form-group mx-sm-3 mb-2">
        <label for="nuevo_nombre_mundo" class="sr-only">Nombre del mundo</label>
        <input type="text" value="{{$Nombre_mundo}}" name="nuevo_nombre_mundo" class="form-control" id="nuevo_nombre_mundo" placeholder="Ej: Córdoba">
        @error('nuevo_nombre_mundo')
        <small style="color: red">{{$message}}</small>
        @enderror
        <button type="submit" class="btn btn-primary">Cambiar</button>
      </div>
      <input type="hidden" name="id" id="id" value="Nombre_mundo">
    </form>

    </div>
    <div class="col-8">
    <form id="form-edit-fecha_mundo" class="form-inline" action="{{route('config.update_fecha_mundo')}}" method="POST">
      @csrf
      <div class="form-group mb-2">
        <label for="nombre_mundo" class="sr-only">Fecha actual</label>
        <input type="text" readonly class="form-control-plaintext" id="nombre_mundo" value="Fecha actual">
      </div>
      <div class="form-group mx-sm-3 mb-2">
        <input type="text" id="dia" name="dia" class="form-control col-2" placeholder="Día">
        @error('dia')
        <small style="color: red">{{$message}}</small>
        @enderror
        <select class="form-select form-control col-4" type="number" id="mes" name="mes">
          <option selected disabled value="">Mes</option>
          <option value="0">Semana de año nuevo</option>
          <option value="1">Enero</option>
          <option value="2">Febrero</option>
          <option value="3">Marzo</option>
          <option value="4">Abril</option>
          <option value="5">Mayo</option>
          <option value="6">Junio</option>
          <option value="7">Julio</option>
          <option value="8">Agosto</option>
          <option value="9">Septiembre</option>
          <option value="10">Octubre</option>
          <option value="11">Noviembre</option>
          <option value="12">Diciembre</option>
        </select>
        @error('mes')
        <small style="color: red">{{$message}}</small>
        @enderror
        <input type="text" id="anno" name="anno" class="form-control col-2" placeholder="Año">
        @error('anno')
        <small style="color: red">{{$message}}</small>
        @enderror
        <button type="submit" class="btn btn-primary">Cambiar</button>
      </div>
    </form>
      
    </div>

  </div>
  <!--<a href="{{route('galeria.limpiar_imagenes')}}" class="btn btn-dark">Limpiar imágenes</a>-->
  <div class="row">

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Tipos de asentamientos</h5>
      </div>
      <div class="card-body overflow-auto" style="height: 300px;">
        <table class="table table-sm table-hover table-striped table-dark">
          @if (Arr::has($tipos_asentamiento, 'error.error'))
          <thead class="bg-dark">
          </thead>
          <tbody>
            <tr>
              <td>
                {{Arr::get($tipos_asentamiento, 'error.error')}}
              </td>
            </tr>
          </tbody>
          @else
          <thead class="bg-dark">
            <tr>
              <th>Nombre</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody id="tipos_asentamiento_tabla">
            @foreach($tipos_asentamiento as $tipo_asentamiento)
            <tr>
              <td>{{$tipo_asentamiento->nombre}}</td>
              <td>
                <button id="{{$tipo_asentamiento->id}}" nombre="{{$tipo_asentamiento->nombre}}" tipo="asentamiento" title="Editar" class="editar-tipo btn btn-sm btn-success" data-toggle="modal" data-target="#editar_nombre"><i class="fas fa-pencil-alt"></i></button>
                <button id="{{$tipo_asentamiento->id}}" nombre="{{$tipo_asentamiento->nombre}}" tipo="asentamiento" title="Borrar" class="borrar-tipo btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmar_eliminacion"><i class="fas fa-times-circle"></i></button>
              </td>
            </tr>
            @endforeach
          </tbody>
          @endif
        </table>
      </div>
      <div class="card-footer">
        <label for="nuevoTimeline" class="form-label">Añadir nuevo</label>
        <form id="form-add-tipo-asentamiento" class="row" action="{{route('config.store_tipo_asentamiento')}}" method="POST">
          @csrf
          <div class="col">
            <input type="text" value="{{old('nuevo_tipo_asentamiento')}}" name="nuevo_tipo_asentamiento" class="form-control" id="nuevo_tipo_asentamiento" placeholder="Ej: Pueblo">
            @error('nuevo_tipo_asentamiento')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-3 align-bottom">
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </form>
      </div>
    </div><!--card -->

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Tipos de conflictos</h5>
      </div>
      <div class="card-body overflow-auto" style="height: 300px;">
        <table class="table table-sm table-hover table-striped table-dark">
          @if (Arr::has($tipos_conflicto, 'error.error'))
          <thead class="bg-dark">
          </thead>
          <tbody>
            <tr>
              <td>
                {{Arr::get($tipos_conflicto, 'error.error')}}
              </td>
            </tr>
          </tbody>
          @else
          <thead class="bg-dark">
            <tr>
              <th>Nombre</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody id="tipos_conflicto_tabla">
            @foreach($tipos_conflicto as $tipo_conflicto)
            <tr>
              <td>{{$tipo_conflicto->nombre}}</td>
              <td>
                <button id="{{$tipo_conflicto->id}}" nombre="{{$tipo_conflicto->nombre}}" tipo="conflicto" title="Editar" class="editar-tipo btn btn-sm btn-success" data-toggle="modal" data-target="#editar_nombre"><i class="fas fa-pencil-alt"></i></button>
                <button id="{{$tipo_conflicto->id}}" nombre="{{$tipo_conflicto->nombre}}" tipo="conflicto" title="Borrar" class="borrar-tipo btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmar_eliminacion"><i class="fas fa-times-circle"></i></button>
              </td>
            </tr>
            @endforeach
          </tbody>
          @endif
        </table>
      </div>
      <div class="card-footer">
        <label for="nuevoTimeline" class="form-label">Añadir nuevo</label>
        <form id="form-add-tipo-conflicto" class="row" action="{{route('config.store_tipo_conflicto')}}" method="POST">
          @csrf
          <div class="col">
            <input type="text" value="{{old('nuevo_tipo_conflicto')}}" name="nuevo_tipo_conflicto" class="form-control" id="nuevo_tipo_conflicto" placeholder="Ej: Guerra">
            @error('nuevo_tipo_conflicto')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-3 align-bottom">
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </form>
      </div>
    </div><!--card -->

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Tipos de lugares</h5>
      </div>
      <div class="card-body overflow-auto" style="height: 300px;">
        <table class="table table-sm table-hover table-striped table-dark">
          @if (Arr::has($tipos_lugar, 'error.error'))
          <thead class="bg-dark">
          </thead>
          <tbody>
            <tr>
              <td>
                {{Arr::get($tipos_lugar, 'error.error')}}
              </td>
            </tr>
          </tbody>
          @else
          <thead class="bg-dark">
            <tr>
              <th>Nombre</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody id="tipos_lugar_tabla">
            @foreach($tipos_lugar as $tipo_lugar)
            <tr>
              <td>{{$tipo_lugar->nombre}}</td>
              <td>
                <button id="{{$tipo_lugar->id}}" nombre="{{$tipo_lugar->nombre}}" tipo="lugar" title="Editar" class="editar-tipo btn btn-sm btn-success" data-toggle="modal" data-target="#editar_nombre"><i class="fas fa-pencil-alt"></i></button>
                <button id="{{$tipo_lugar->id}}" nombre="{{$tipo_lugar->nombre}}" tipo="lugar" title="Borrar" class="borrar-tipo btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmar_eliminacion"><i class="fas fa-times-circle"></i></button>
              </td>
            </tr>
            @endforeach
          </tbody>
          @endif
        </table>
      </div>
      <div class="card-footer">
        <label for="nuevoTimeline" class="form-label">Añadir nuevo</label>
        <form id="form-add-tipo-lugar" class="row" action="{{route('config.store_tipo_lugar')}}" method="POST">
          @csrf
          <div class="col">
            <input type="text" value="{{old('nuevo_tipo_lugar')}}" name="nuevo_tipo_lugar" class="form-control" id="nuevo_tipo_lugar" placeholder="Ej: Volcán">
            @error('nuevo_tipo_lugar')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-3 align-bottom">
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </form>
      </div>
    </div><!--card -->
  </div>

  <div class="row">

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Tipos de organizaciones</h5>
      </div>
      <div class="card-body overflow-auto" style="height: 300px;">
        <table class="table table-sm table-hover table-striped table-dark">
          @if (Arr::has($tipos_organizaciones, 'error.error'))
          <thead class="bg-dark">
          </thead>
          <tbody>
            <tr>
              <td>
                {{Arr::get($tipos_organizaciones, 'error.error')}}
              </td>
            </tr>
          </tbody>
          @else
          <thead class="bg-dark">
            <tr>
              <th>Nombre</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody id="tipos_organizaciones_tabla">
            @foreach($tipos_organizaciones as $tipo_organizacion)
            <tr>
              <td>{{$tipo_organizacion->nombre}}</td>
              <td>
                <button id="{{$tipo_organizacion->id}}" nombre="{{$tipo_organizacion->nombre}}" tipo="organizacion" title="Editar" class="editar-tipo btn btn-sm btn-success" data-toggle="modal" data-target="#editar_nombre"><i class="fas fa-pencil-alt"></i></button>
                <button id="{{$tipo_organizacion->id}}" nombre="{{$tipo_organizacion->nombre}}" tipo="organizacion" title="Borrar" class="borrar-tipo btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmar_eliminacion"><i class="fas fa-times-circle"></i></button>
              </td>
            </tr>
            @endforeach
          </tbody>
          @endif
        </table>
      </div>
      <div class="card-footer">
        <label for="nuevoTimeline" class="form-label">Añadir nuevo</label>
        <form id="form-add-tipo-organizacion" class="row" action="{{route('config.store_tipo_organizacion')}}" method="POST">
          @csrf
          <div class="col">
            <input type="text" value="{{old('nuevo_tipo_organizacion')}}" name="nuevo_tipo_organizacion" class="form-control" id="nuevo_tipo_organizacion" placeholder="Ej: Reino">
            @error('nuevo_tipo_organizacion')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-3 align-bottom">
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </form>
      </div>
    </div><!--card -->

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Líneas cronológicas</h5>
      </div>
      <div class="card-body overflow-auto" style="height: 300px;">
        <table class="table table-sm table-hover table-striped table-dark">
          @if (Arr::has($lineas_temporales, 'error.error'))
          <thead class="bg-dark">
          </thead>
          <tbody>
            <tr>
              <td>
                {{Arr::get($lineas_temporales, 'error.error')}}
              </td>
            </tr>
          </tbody>
          @else
          <thead class="bg-dark">
            <tr>
              <th>Nombre</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody id="lineas_temporales_tabla">
            @foreach($lineas_temporales as $linea_temporal)
            <tr>
              <td>{{$linea_temporal->nombre}}</td>
              <td>
                <button id="{{$linea_temporal->id}}" nombre="{{$linea_temporal->nombre}}" tipo="linea_temporal" title="Editar" class="editar-tipo btn btn-sm btn-success" data-toggle="modal" data-target="#editar_nombre"><i class="fas fa-pencil-alt"></i></button>
                <button id="{{$linea_temporal->id}}" nombre="{{$linea_temporal->nombre}}" tipo="linea_temporal" title="Borrar" class="borrar-tipo btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmar_eliminacion"><i class="fas fa-times-circle"></i></button>
              </td>
            </tr>
            @endforeach
          </tbody>
          @endif
        </table>
      </div>
      <div class="card-footer">
        <label for="nuevoTimeline" class="form-label">Añadir nuevo</label>
        <form id="form-add-linea-temporal" class="row" action="{{route('config.store_linea_temporal')}}" method="POST">
          @csrf
          <div class="col">
            <input type="text" value="{{old('nueva_linea_temporal')}}" name="nueva_linea_temporal" class="form-control" id="nueva_linea_temporal" placeholder="Ej: Edad de la Muerte">
            @error('nueva_linea_temporal')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-3 align-bottom">
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </form>
      </div>
    </div><!--card -->
  </div>
</div>
<!-- /.col -->

@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
<script>
  $(function() {
    $('#dia').val('{{$fecha->dia}}');
    $('#mes').val('{{$fecha->mes}}');
    $('#anno').val('{{$fecha->anno}}');
  });
</script>
<script>
  @if(Session::has('message'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 600,
    "preventDuplicates": true,
  }
  toastr.success("{{ session('message') }}");
  @endif

  @if(Session::has('error'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 900,
    "preventDuplicates": true,
  }
  toastr.error("{{ session('error') }}");
  @endif

  @if(Session::has('info'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 600,
    "preventDuplicates": true,
  }
  toastr.info("{{ session('info') }}");
  @endif

  @if(Session::has('warning'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 600,
    "preventDuplicates": true,
  }
  toastr.warning("{{ session('warning') }}");
  @endif
</script>
@endsection