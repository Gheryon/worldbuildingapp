@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Relatos e historias</title>
@endsection

@section('navbar-buttons')
<li class="nav-item">
  <a href="{{route('relatos.create')}}" class="btn btn-dark">Nuevo</a>
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
<!--<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('relatos.search')}}" method="GET">
    <div class="input-group">
      <input type="search" name="search" class="form-control" placeholder="Nombre a buscar">
      <div class="input-group-append">
        <button type="submit" class="btn btn-default">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </div>
  </form>
</li>-->
@endsection

@section('content')
<div class="modal fade" id="eliminar-relato" tabindex="-1" role="dialog" aria-labelledby="confirmar_eliminacion" aria-hidden="true">
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
          <form id="form-confirmar-borrar" class="col-md-auto" action="{{route('relatos.destroy')}}" method="POST">
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
    @if (Arr::has($relatos, 'error.error'))
    <div class="text-center">
      {{Arr::get($relatos, 'error.error')}}
    </div>
    @else
    @if($relatos->count()>0)
    <table class="table table-bordered table-sm table-striped table-hover">
      <thead>
        <tr>
          <th>Nombre</th>
          <th style="text-align:right">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($relatos as $relato)
        <tr>
          <td>{{$relato->nombre}}</td>
          <td style="text-align:right">
            <div class="btn-group" role="group" aria-label="Basic example">
              <a href="{{route('relatos.show',$relato->id_articulo)}}" role="button" title="Ver" class="btn btn-info detalles">
                <i class="fas fa-id-card mr-1"></i>
              </a>
              <a href="{{route('relatos.edit',$relato->id_articulo)}}" role="button" title="Editar" class="btn btn-success"><i class="fas fa-pencil-alt"></i></a>
              <button data-id="{{$relato->id_articulo}}" data-nombre="{{$relato->nombre}}" type="button" title="borrar" class="borrar btn btn-danger" data-toggle="modal" data-target="#eliminar-relato"><i class="fas fa-trash"></i></button>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
      @else
      <div class="col-12">
        <h5 class="card-title">No hay elementos almacenados</h5>
      </div>
      </br>
      <div class="col-12 mt-3">
        <a href="{{route('relatos.create')}}" class="btn btn-dark">Añadir nuevo</a>
      </div>
      @endif
    </table>

    @endif
  </div>
  <!-- /.col -->

</div>

@endsection

@section('specific-scripts')
<!-- relatos javascript -->
<script src="{{asset('dist/js/config.js')}}"></script>
<script>
  $(function() {
    $(document).on('change', '#order', function() {
      orden = this.value;
      let url = "{{ route('relatos', ['orden'=>'_orden']) }}";
      url = url.replace('_orden', orden);
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