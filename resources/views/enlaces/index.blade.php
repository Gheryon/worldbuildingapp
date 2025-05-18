@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Enlaces</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<button title="Nuevo" class="btn btn-dark" data-toggle="modal" data-target="#nuevo_enlace">Añadir enlace</button>
</li>
@endsection

@section('content')
<div class="row">
  <h1>Enlaces</h1>
</div>
<hr>

<!-- Modal -->
<div class="modal fade" id="confirmar_eliminacion" tabindex="-1" role="dialog" aria-labelledby="confirmarEliminacionLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-danger">
        <div class="card-header">
          <h5 class="card-title" id="confirmarEliminacionLabel">Confirmar eliminación</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <div class="row">
            <p> ¿Borrar enlace: <span id="nombre-borrar"> </span>?</p>
          </div>
        </div>
        <div class="card-footer">
          <form id="form-confirmar-borrar" class="col-md-auto" action="{{route('enlace.destroy')}}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id_borrar" id="id_borrar">
            <button type="button" id="cancelar-borrar-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="confirmar-borrar-button" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="nuevo_enlace" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="nuevoEnlaceLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-dark">
        <div class="card-header">
          <h5 class="card-title" id="nuevoEnlaceLabel">Nuevo enlace</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-nuevo_enlace" class="col-md-auto" action="{{route('enlace.store')}}" method="POST">
        <div class="card-body">
            @csrf
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" required>
            <div class="invalid-feedback">
              Nombre no puede estar vacío.
            </div>
            <label for="url" class="form-label">Url</label>
            <input type="text" name="url" class="form-control" id="url" required>
            <div class="invalid-feedback">
              Url no puede estar vacía.
            </div>
            <label for="tipo" class="form-label">Tipo</label>
            <select class="form-select form-control" name="tipo" id="tipo" required>
              <option selected disabled value="">Elegir</option>
                <option value="generador">Generador</option>
                <option value="criatura">Criatura</option>
                <option value="referencia">Referencia</option>
            </select>
            <div class="invalid-feedback">
              Tipo necesario.
            </div>
        </div>
        <div class="card-footer">
          <button type="button" id="cancelar-nuevo-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
          <button type="submit" id="submit-nuevo-button" class="btn btn-success">Guardar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editar_enlace" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editarEnlaceLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-dark">
        <div class="card-header">
          <h5 class="card-title" id="editarEnlaceLabel">Editar enlace</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-editar_enlace" class="col-md-auto" action="{{route('enlace.update')}}" method="POST">
        <div class="card-body">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_editar" id="id_editar">
            <label for="nombre_editar" class="form-label">Nombre</label>
            <input type="text" name="nombre_editar" class="form-control" id="nombre_editar" required>
            <div class="invalid-feedback">
              Nombre no puede estar vacío.
            </div>
            <label for="url_editar" class="form-label">Url</label>
            <input type="text" name="url_editar" class="form-control" id="url_editar" required>
            <div class="invalid-feedback">
              Url no puede estar vacía.
            </div>
            <label for="tipo_editar" class="form-label">Tipo</label>
            <select class="form-select form-control" name="tipo_editar" id="tipo_editar" required>
              <option selected disabled value="">Elegir</option>
                <option value="generador">Generador</option>
                <option value="criatura">Criatura</option>
                <option value="referencia">Referencia</option>
            </select>
            <div class="invalid-feedback">
              Tipo necesario.
            </div>
        </div>
        <div class="card-footer">
          <button type="button" id="cancelar-editar-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
          <button type="submit" id="submit-editar-button" class="btn btn-success">Guardar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="col-md-12">
  <div class="row">

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Generadores</h5>
      </div>
      <div class="card-body overflow-auto">
        @if (Arr::has($generadores, 'error.error'))
          {{Arr::get($generadores, 'error.error')}}
        @else
          @if($generadores->count()>0)
            @foreach($generadores as $generador)
            <div class="row">
              <button data-id="{{$generador->id}}" data-nombre="{{$generador->nombre}}" data-tipo="{{$generador->tipo}}" data-url="{{$generador->url}}" title="Editar" class="editar-enlace btn btn-sm btn-success" data-toggle="modal" data-target="#editar_enlace"><i class="fas fa-pencil-alt"></i></button>
              <button data-id="{{$generador->id}}" data-nombre="{{$generador->nombre}}" title="Borrar" class="borrar btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmar_eliminacion"><i class="fas fa-times-circle"></i></button>
              <a href="{{$generador->url}}" class="ml-2">{{$generador->nombre}}</a>
            </div>        
            @endforeach
          @else
            <h5 class="card-title">No hay enlaces almacenados</h5>
            <br>
            <button title="Nuevo" class="btn btn-dark mt-3" data-toggle="modal" data-target="#nuevo_enlace">Añadir enlace</button>
          @endif
        @endif
      </div>
      <div class="card-footer">
      </div>
    </div><!--card -->

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Criaturas fantásticas</h5>
      </div>
      <div class="card-body overflow-auto">
        @if (Arr::has($criaturas, 'error.error'))
          {{Arr::get($criaturas, 'error.error')}}
        @else
          @if($generadores->count()>0)
            @foreach($criaturas as $criatura)
            <div class="row">
              <button data-id="{{$criatura->id}}" data-nombre="{{$criatura->nombre}}" data-tipo="{{$criatura->tipo}}" data-url="{{$criatura->url}}" title="Editar" class="editar-enlace btn btn-sm btn-success" data-toggle="modal" data-target="#editar_enlace"><i class="fas fa-pencil-alt"></i></button>
              <button data-id="{{$criatura->id}}" data-nombre="{{$criatura->nombre}}" title="Borrar" class="borrar btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmar_eliminacion"><i class="fas fa-times-circle"></i></button>
              <a href="{{$criatura->url}}" class="ml-2">{{$criatura->nombre}}</a>
            </div>
            @endforeach
          @else
            <h5 class="card-title">No hay enlaces almacenados</h5>
            <br>
            <button title="Nuevo" class="btn btn-dark mt-3" data-toggle="modal" data-target="#nuevo_enlace">Añadir enlace</button>
          @endif
        @endif
      </div>
      <div class="card-footer">
      </div>
    </div><!--card -->

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Referencias</h5>
      </div>
      <div class="card-body overflow-auto">
      @if (Arr::has($referencias, 'error.error'))
          {{Arr::get($referencias, 'error.error')}}
        @else
          @if($generadores->count()>0)
            @foreach($referencias as $referencia)
            <div class="row">
              <button data-id="{{$referencia->id}}" data-nombre="{{$referencia->nombre}}" data-tipo="{{$referencia->tipo}}" data-url="{{$referencia->url}}" title="Editar" class="editar-enlace btn btn-sm btn-success" data-toggle="modal" data-target="#editar_enlace"><i class="fas fa-pencil-alt"></i></button>
              <button data-id="{{$referencia->id}}" data-nombre="{{$referencia->nombre}}" title="Borrar" class="borrar btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmar_eliminacion"><i class="fas fa-times-circle"></i></button>
              <a href="{{$referencia->url}}" class="ml-2">{{$referencia->nombre}}</a>
            </div>        
            @endforeach
          @else
            <h5 class="card-title">No hay enlaces almacenados</h5>
            <br>
            <button title="Nuevo" class="btn btn-dark mt-3" data-toggle="modal" data-target="#nuevo_enlace">Añadir enlace</button>
          @endif
        @endif
      </div>
      <div class="card-footer">
      </div>
    </div><!--card -->
  </div>
</div>
<!-- /.col -->

@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
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