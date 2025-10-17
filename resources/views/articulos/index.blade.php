@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Artículos</title>
@endsection

@section('navbar-buttons')
<li class="nav-item">
  <a href="{{route('articulos.create')}}" class="btn btn-dark">Nuevo articulo</a>
</li>
<li class="nav-item">
  <select id="filtro" class="form-control ml-2" name="filtro">
    <option selected disabled value="ASC">Filtro</option>
    <option value="all">Todos</option>
    <option value="Referencia">Referencia</option>
    <option value="Canon">Canon</option>
  </select>
</li>
<li class="nav-item ml-2">
  <select id="order" class="form-control ml-2" name="order">
    <option selected disabled value="ASC">Orden</option>
    <option value="asc">Ascendente</option>
    <option value="desc">Descendente</option>
  </select>
</li>
@endsection

@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('articulos.search')}}" method="GET">
    <div class="input-group">
      <input type="search" name="search" class="form-control" placeholder="Nombre a buscar">
      <div class="input-group-append">
        <button type="submit" class="btn btn-default">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </div>
  </form>
</li>
@endsection

@section('content')
<div class="modal fade" id="eliminar-articulo" tabindex="-1" role="dialog" aria-labelledby="confirmar_eliminacion" aria-hidden="true">
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
    <div class="text-center">
      {{Arr::get($articulos, 'error.error')}}
    </div>
    @else
    @if($articulos->count()>0)
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
          <td style="text-align:center">
            <div class="btn-group" role="group" aria-label="Basic example">
              <a href="{{route('articulos.show',$articulo->id_articulo)}}" role="button" title="Ver" class="btn btn-info detalles">
                <i class="fas fa-id-card mr-1"></i>
              </a>
              <a href="{{route('articulos.edit',$articulo->id_articulo)}}" role="button" title="Editar" class="btn btn-success"><i class="fas fa-pencil-alt"></i></a>
              <button data-id="{{$articulo->id_articulo}}" data-nombre="{{$articulo->nombre}}" type="button" title="borrar" class="borrar btn btn-danger" data-toggle="modal" data-target="#eliminar-articulo"><i class="fas fa-trash"></i></button>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
      @else
      <div class="col-12">
        <h5 class="card-title">No hay artículos almacenados</h5>
      </div>
      </br>
      <div class="col-12 mt-3">
        <a href="{{route('articulos.create')}}" class="btn btn-dark">Añadir nuevo articulo</a>
      </div>
      @endif
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
  $(function() {
    $(document).on('change', '#filtro', function() {
      filtro = this.value;
      orden = "{{$orden}}";
      let url = "{{ route('articulos', ['orden'=>'_orden', 'filtro'=>'_filtro']) }}";
      url = url.replace('_filtro', filtro);
      url = url.replace('_orden', orden);
      document.location.href = url;
    });
    $(document).on('change', '#order', function() {
      orden = this.value;
      filtro = "{{$filtro_o}}";
      let url = "{{ route('articulos', ['orden'=>'_orden', 'filtro'=>'_filtro']) }}";
      url = url.replace('_orden', orden);
      url = url.replace('_filtro', filtro);
      document.location.href = url;
    });
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