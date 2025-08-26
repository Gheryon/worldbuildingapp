@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Personajes</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('personaje.create')}}" class="btn btn-dark">Nuevo personaje</a>
</li>
<li class="nav-item ml-2">
<select id="filter_tipo" class="form-control ml-2" name="filter_tipo">
<option selected disabled value="0">Filtrar especie</option>
<option value="0">Todas</option>
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
  <form class="form-inline ml-2" action="{{route('personajes.search')}}" method="GET">
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
<h1>Personajes</h1>

<div class="modal fade" id="eliminar-personaje" tabindex="-1" role="dialog" aria-labelledby="eliminar-personaje" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="card card-danger">
        <div class="card-header">
          <h3 class="card-title">Eliminar personaje</h3>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-borrar-personaje" class="text-center" action="{{route('personaje.destroy')}}" method="POST">
          @csrf
          @method('DELETE')
          <div class="card-body">
            <div class="input-group mb-3">
              <p> ¿Borrar personaje: <span id="nombre-borrar"> </span>?</p>
              <input type="hidden" id="id_borrar" name="id_borrar">
              <input type="hidden" name="nombre_borrado" id="nombre_borrado">
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
@if (Arr::has($personajes, 'error.error'))
  {{Arr::get($personajes, 'error.error')}}
@else
@if($personajes->count()>0)
@foreach($personajes as $personaje)
<div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
  <div class="card card-dark card-outline">
    <div class="card-body box-profile">
      <div class="text-center">
        <img class="profile-user-img img-fluid img-circle" src="{{asset("storage/retratos/{$personaje->Retrato}")}}" alt="User profile picture">
      </div>
      <h3 class="profile-username text-center">{{$personaje->Nombre}}</h3>
      <ul class="list-group list-group-unbordered">
        <li class="list-group-item">
          <b><i class="fa-solid fa-dna"></i> Especie</b> <a class="float-right">{{$personaje->especie}}</a>
        </li>
        <li class="list-group-item">
          <b><i class="fas fa-lg fa-smile-wink"></i> Sexo</b> <a class="float-right">{{$personaje->Sexo}}</a>
        </li>
      </ul>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
      <div class="row text-right">
        <a href="{{route('personaje.show',$personaje->id)}}" role="button" title="Ver" class="btn btn-info btn-sm col-4"><b><i class="fas fa-id-card mr-1"></i></b></a>
        <a href="{{route('personaje.edit',$personaje->id)}}" role="button" title="Editar" class="btn btn-success btn-sm col-4"><b><i class="fas fa-pencil-alt mr-1"></i></b></a>
        <button data-id="{{$personaje->id}}" data-nombre="{{$personaje->Nombre}}" type="button" title="Borrar" class="borrar btn btn-danger btn-sm col-4" data-toggle="modal" data-target="#eliminar-personaje"><i class="fas fa-trash mr-1"></i></button>
      </div>
    </div>
  </div>
</div>
@endforeach
  @else
  <div class="col-12">
    <h5 class="card-title">No hay personajes almacenados</h5>
  </div>
  </br>
  <div class="col-12 mt-3">
    <a href="{{route('personaje.create')}}" class="btn btn-dark">Crear nuevo personaje</a>
  </div>
  @endif
</div>
@endif

@endsection

@section('specific-scripts')
<!-- articulos javascript -->
<script src="{{asset('dist/js/config.js')}}"></script>
<script>
  $(function() {

    $(document).on('change', '#order', function(){
      orden=this.value;
      tipo="{{$tipo_o}}";
      let url = "{{ route('personajes.index', ['orden'=>'_orden', 'tipo'=>'_tipo']) }}";
      url = url.replace('_orden', orden);
      url = url.replace('_tipo', tipo);
      document.location.href=url;
    });

    $(document).on('change', '#filter_tipo', function(){
      tipo=this.value;
      orden="{{$orden}}";
      let url = "{{ route('personajes.index', ['orden'=>'_orden', 'tipo'=>'_tipo']) }}";
      url = url.replace('_orden', orden);
      url = url.replace('_tipo', tipo);
      document.location.href=url;
    });
  });
</script>
<script>
  @if(Session::has('message'))
  toastr.options =
  {
  	"closeButton" : true,
    "closeOnHover" : true,
  	"progressBar" : false,
    "showDuration" : 600,
    "preventDuplicates" : true,
  }
  		toastr.success("{{ session('message') }}");
  @endif

  @if(Session::has('error'))
  toastr.options =
  {
  	"closeButton" : true,
    "closeOnHover" : true,
  	"progressBar" : false,
    "showDuration" : 900,
    "preventDuplicates" : true,
  }
  		toastr.error("{{ session('error') }}");
  @endif

  @if(Session::has('info'))
  toastr.options =
  {
  	"closeButton" : true,
    "closeOnHover" : true,
  	"progressBar" : false,
    "showDuration" : 600,
    "preventDuplicates" : true,
  }
  		toastr.info("{{ session('info') }}");
  @endif

  @if(Session::has('warning'))
  toastr.options =
  {
  	"closeButton" : true,
    "closeOnHover" : true,
  	"progressBar" : false,
    "showDuration" : 600,
    "preventDuplicates" : true,
  }
  		toastr.warning("{{ session('warning') }}");
  @endif
</script>
@endsection