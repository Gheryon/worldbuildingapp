@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Personajes</title>
@endsection

@section('navbar-buttons')
<a href="{{route('personaje.create')}}" class="btn btn-dark">Nuevo personaje</a>
@endsection

@section('navbar-search')
  <li class="nav-item">
    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
      <i class="fas fa-search"></i>
    </a>
    <div class="navbar-search-block">
      <form class="form-inline" action="{{route('personajes.search')}}" method="GET">
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
              <p> ¿Borrar personaje: <span id="nombre-personaje-borrar"> </span>?</p>
              <input type="hidden" id="id_personaje" name="id_personaje">
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

@foreach($personajes as $personaje)
<div class="col-4 col-sm-12 col-md-4 col-lg-3">
  <div class="card card-dark card-outline">
    <div class="card-body box-profile">
      <div class="text-center">
        <img class="profile-user-img img-fluid img-circle" src="{{asset("storage/retratos/{$personaje->Retrato}")}}" alt="User profile picture">
      </div>
      <h3 class="profile-username text-center">{{$personaje->Nombre}}</h3>
      <p class="text-muted">{!!$personaje->DescripcionShort!!}</p>
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
        <a href="{{route('personaje.show',$personaje->id)}}" type="button" title="Ver" class="btn btn-info btn-sm col-4"><b><i class="fas fa-id-card mr-1"></i></b></a>
        <a href="{{route('personaje.edit',$personaje->id)}}" type="button" title="Editar" class="btn btn-success btn-sm col-4"><b><i class="fas fa-pencil-alt mr-1"></i></b></a>
        <button id="{{$personaje->id}}" nombre="{{$personaje->Nombre}}" type="button" title="Borrar" class="borrar btn btn-danger btn-sm col-4" data-toggle="modal" data-target="#eliminar-personaje"><i class="fas fa-trash mr-1"></i></button>
      </div>
    </div>
  </div>
</div>
@endforeach
</div>
@endif

@endsection

@section('specific-scripts')
<!-- articulos javascript -->
<script src="{{asset('dist/js/personajes.js')}}"></script>
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