@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Conflictos</title>
@endsection

@section('navbar-buttons')
<a href="{{route('conflicto.create')}}" class="btn btn-dark">Nuevo conflicto</a>
<select id="filter_tipo" class="form-select ml-2" name="filter_tipo">
<option selected disabled value="0">Filtrar tipo</option>
<option value="0">Todos</option>
@foreach($tipos as $tipo)
<option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
@endforeach
</select>
<select id="order" class="form-select ml-2" name="order">
  <option selected disabled value="ASC">Orden</option>
  <option value="asc">Ascendente</option>
  <option value="desc">Descendente</option>
</select>
@endsection

@section('navbar-search')
  <li class="nav-item">
    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
      <i class="fas fa-search"></i>
    </a>
    <div class="navbar-search-block">
      <form class="form-inline" action="{{route('conflictos.search')}}" method="GET">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Nombre del conflicto a buscar" name="search" id="search" aria-label="Search">
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
<div class="row">
  <h1>Conflictos y batallas</h1>
</div>
<hr>

<!-- Modal -->
<div class="modal fade" id="eliminar-conflicto" tabindex="-1" role="dialog" aria-labelledby="Confirmar eliminacion" aria-hidden="true">
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
            <p> ¿Borrar conflicto: <span id="nombre-borrar"> </span>?</p>
          </div>
        </div>
        <div class="card-footer">
          <form id="form-confirmar-borrar" class="col-md-auto" action="{{route('conflicto.destroy')}}" method="POST">
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

<div class="row">
@if (Arr::has($conflictos, 'error.error'))
<div class="text-center">No se encontraron conflictos.
{{Arr::get($conflictos, 'error.error')}}</div>
@else
@foreach($conflictos as $conflicto)
<div class="col col-sm-12 col-md-4 col-lg-4">
  <div class="card card-dark card-outline">
    <div class="card-body box-profile">
      <h3 class="profile-username text-center">{{$conflicto->nombre}}</h3>
      <ul class="list-group list-group-unbordered mb-3">
        <li class="list-group-item">
          <b>Tipo:</b> {{$conflicto->tipo}}
        </li>
        <li class="list-group-item">
          <b>Descripción breve: </b>{!!$conflicto->descripcion!!}
        </li>
      </ul>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
      <div class="row text-right">
        <a href="{{route('conflicto.show',$conflicto->id)}}" type="button" title="Ver" class="btn btn-info btn-sm col-4"><b><i class="fas fa-id-card mr-1"></i></b></a>
        <a href="{{route('conflicto.edit',$conflicto->id)}}" type="button" title="Editar" class="btn btn-success btn-sm col-4"><b><i class="fas fa-pencil-alt mr-1"></i></b></a>
        <button id="{{$conflicto->id}}" nombre="{{$conflicto->nombre}}" type="button" title="Borrar" class="borrar btn btn-danger btn-sm col-4" data-toggle="modal" data-target="#eliminar-conflicto"><i class="fas fa-trash mr-1"></i></button>
      </div>
    </div>
  </div>
</div>
@endforeach
@endif
</div>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
<script>
  $(function() {

    $(document).on('change', '#order', function(){
      orden=this.value;
      tipo="{{$tipo_o}}";
      let url = "{{ route('conflictos.index', ['orden'=>'_orden', 'tipo'=>'_tipo']) }}";
      url = url.replace('_orden', orden);
      url = url.replace('_tipo', tipo);
      document.location.href=url;
    });

    $(document).on('change', '#filter_tipo', function(){
      tipo=this.value;
      orden="{{$orden}}";
      let url = "{{ route('conflictos.index', ['orden'=>'_orden', 'tipo'=>'_tipo']) }}";
      url = url.replace('_orden', orden);
      url = url.replace('_tipo', tipo);
      document.location.href=url;
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