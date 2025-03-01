@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Artículos</title>
@endsection

@section('navbar-buttons')
<a href="{{route('articulos.create')}}" class="btn btn-dark">Nuevo articulo</a>
@endsection

@section('navbar-search')
<li class="nav-item">
  <a class="nav-link" data-widget="navbar-search" href="#" role="button">
    <i class="fas fa-search"></i>
  </a>
  <div class="navbar-search-block">
    <form class="form-inline" action="{{route('articulos.search')}}" method="GET">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Nombre a buscar" name="search" id="search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
          <button class="btn btn-navbar" type="button" data-widget="navbar-search">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    </form>
  </div>
</li>
@endsection

@section('content')
<div class="modal fade" id="eliminar-articulo" tabindex="-1" role="dialog" aria-labelledby="Confirmar eliminacion" aria-hidden="true">
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
            <p> ¿Borrar artículo: <span id="nombre-borrar"> </span>?</p>
          </div>
        </div>
        <div class="card-footer">
          <form id="form-confirmar-borrar" class="col-md-auto" action="{{route('articulos.destroy')}}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id_borrar" id="id_borrar">
            <input type="hidden" name="tipo" id="tipo">
            <input type="hidden" name="nombre_borrado" id="nombre_borrado">
            <button type="button" id="cancelar-borrar-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="confirmar-borrar-button" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    @if (Arr::has($articulos, 'error.error'))
    <div class="text-center">No se encontraron artículos.
      {{Arr::get($articulos, 'error.error')}}
    </div>
    @else
    <table class="table table-bordered table-sm table-striped table-hoover">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>tipo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($articulos as $articulo)
        <tr>
          <td>{{$articulo->nombre}}</td>
          <td>{{$articulo->tipo}}</td>
          <td style="text-align:center" artId="{{$articulo->id_articulo}}" artNombre="{{$articulo->nombre}}">
            <div class="btn-group" role="group" aria-label="Basic example">
              <a href="{{route('articulos.show',$articulo->id_articulo)}}" type="button" title="Ver" class="btn btn-info detalles">
                <i class="fas fa-id-card mr-1"></i>
              </a>
              <a href="{{route('articulos.edit',$articulo->id_articulo)}}" type="button" title="Editar" class="btn btn-success"><i class="fas fa-pencil-alt"></i></a>
              <button id="{{$articulo->id_articulo}}" nombre="{{$articulo->nombre}}" type="button" title="borrar" class="borrar btn btn-danger" data-toggle="modal" data-target="#eliminar-articulo"><i class="fas fa-trash"></i></button>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    @endif
  </div>
  <!-- /.col -->

</div>

@endsection

@section('specific-scripts')
<!-- articulos javascript -->
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