@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Lugares</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('lugar.create')}}" class="btn btn-dark">Nuevo lugar</a>
</li>
<li class="nav-item ml-2">
<select id="filter_tipo" class="form-control ml-2" name="filter_tipo">
<option selected disabled value="0">Filtrar tipo</option>
<option value="0">Tipo</option>
@foreach($tipos as $tipo)
<option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
@endforeach
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
  <form class="form-inline ml-2" action="{{route('lugares.search')}}" method="GET">
    <div class="input-group">
      <input type="search" name="search" class="form-control" placeholder="Buscar">
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
<div class="row">
  <h1>Lugares</h1>
</div>
<hr>

<!-- Modal -->
<div class="modal fade" id="eliminar-lugar" tabindex="-1" role="dialog" aria-labelledby="confirmar_eliminacion" aria-hidden="true">
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
            <p> ¿Borrar lugar: <span id="nombre-borrar"> </span>?</p>
          </div>
        </div>
        <div class="card-footer">
          <form id="form-confirmar-borrar" class="col-md-auto" action="{{route('lugar.destroy')}}" method="POST">
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
@if (Arr::has($lugares, 'error.error'))
<div class="text-center">No se encontraron resultados.
{{Arr::get($lugares, 'error.error')}}</div>
@else
@foreach($lugares as $lugar)
<div class="col-4 col-sm-6 col-md-4 col-lg-3">
  <div class="card card-dark card-outline">
    <div class="card-body box-profile">
      <h3 class="profile-username text-center">{{$lugar->nombre}}</h3>
      <ul class="list-group list-group-unbordered mb-3">
        <li class="list-group-item">
          <b>Tipo:</b> {{$lugar->tipo}}
        </li>
        <li class="list-group-item">
          <b>Descripción breve: </b>{!!$lugar->descripcion_breve!!}
        </li>
      </ul>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
      <div class="row text-right">
        <a href="{{route('lugar.show',$lugar->id)}}" role="button" title="Ver" class="btn btn-info btn-sm col-4"><b><i class="fas fa-id-card mr-1"></i></b></a>
        <a href="{{route('lugar.edit',$lugar->id)}}" role="button" title="Editar" class="btn btn-success btn-sm col-4"><b><i class="fas fa-pencil-alt mr-1"></i></b></a>
        <button data-id="{{$lugar->id}}" data-nombre="{{$lugar->nombre}}" type="button" title="Borrar" class="borrar btn btn-danger btn-sm col-4" data-toggle="modal" data-target="#eliminar-lugar"><i class="fas fa-trash mr-1"></i></button>
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
      let url = "{{ route('lugares.index', ['orden'=>'_orden', 'tipo'=>'_tipo']) }}";
      url = url.replace('_orden', orden);
      url = url.replace('_tipo', tipo);
      document.location.href=url;
    });

    $(document).on('change', '#filter_tipo', function(){
      tipo=this.value;
      orden="{{$orden}}";
      let url = "{{ route('lugares.index', ['orden'=>'_orden', 'tipo'=>'_tipo']) }}";
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