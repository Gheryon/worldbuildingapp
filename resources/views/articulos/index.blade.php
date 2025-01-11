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

<div class="modal fade" id="eliminar-articulo" tabindex="-1" role="dialog" aria-labelledby="eliminar-articulo" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="card card-danger">
        <div class="card-header">
          <h3 class="card-title">Eliminar artículo</h3>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-borrar-articulo" class="text-center">
          @csrf
          @method('DELETE')
          <div class="card-body">
            <div class="input-group mb-3">
              <p> ¿Borrar artículo: <span id="nombre-articulo-borrar"> </span>?</p>
              <input type="hidden" id="id_articulo" name="id_articulo">
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn bg-gradient-danger">Eliminar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
<div class="col-md-12">
@if (Arr::has($articulos, 'error.error'))
<div class="text-center">No se encontraron artículos.
{{Arr::get($articulos, 'error.error')}}</div>
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
            <button type="button" title="borrar" class="borrar btn btn-danger" data-toggle="modal" data-target="#eliminar-articulo"><i class="fas fa-trash"></i></button>
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
<script src="{{asset('dist/js/articulos.js')}}"></script>
@endsection