@extends('layouts.index')

@section('title')
<title id="title">Asentamientos</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('asentamiento.create')}}" class="btn btn-dark">Nuevo asentamiento</a>
</li>
<li class="nav-item ml-2">
  <select id="filter_tipo" class="form-control ml-2" name="filter_tipo">
    <option selected disabled value="0">Filtrar tipo</option>
    <option value="0" {{ $tipo_id == 0 ? 'selected' : '' }}>Todos</option>
    @foreach($tipos_asentamientos as $tipo)
    <option value="{{$tipo->id}}" {{ $tipo_id == $tipo->id ? 'selected' : '' }}>{{$tipo->nombre}}</option>
    @endforeach
  </select>
</li>
<x-order-input name="orden" label="Orden" :orden="$orden" />

<li class="nav-item ml-2">
  <a href="{{ route('asentamientos.index') }}" class="btn btn-outline-dark ml-2" title="Limpiar filtros">
    <i class="fas fa-sync-alt"></i>
  </a>
</li>
@endsection

@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('asentamientos.search')}}" method="GET">
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
  <h1>Asentamientos</h1>
</div>
<hr>

<div class="row">
  @forelse($asentamientos as $asentamiento)
  <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
    <div class="card card-dark card-outline">
      <div class="card-body box-profile">
        <h3 class="profile-username text-center">{{$asentamiento->nombre}}</h3>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <div class="row text-right">
          <a href="{{route('asentamiento.show',$asentamiento->id)}}" role="button" title="Ver" class="btn btn-info btn-sm col col-sm-4"><b><i class="fas fa-id-card mr-1"></i></b></a>
          <a href="{{route('asentamiento.edit',$asentamiento->id)}}" role="button" title="Editar" class="btn btn-success btn-sm col col-sm-4"><b><i class="fas fa-pencil-alt mr-1"></i></b></a>
          <button data-id="{{$asentamiento->id}}" data-nombre="{{$asentamiento->nombre}}" type="button" title="Borrar" class="borrar btn btn-danger btn-sm col col-sm-4" data-toggle="modal" data-target="#eliminar-asentamiento"><i class="fas fa-trash mr-1"></i></button>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12 text-center mt-5">
    <div class="callout callout-info">
      <h5>No se encontraron asentamientos</h5>
      <p>Intenta ajustar los filtros o crea uno nuevo.</p>
      <a href="{{route('asentamiento.create')}}" class="btn btn-dark text-light">Crear nuevo asentamiento</a>
    </div>
  </div>
  @endforelse
</div>

<div class="row">
  <div class="col-12 d-flex justify-content-center mt-4">
    {{ $asentamientos->appends(request()->query())->links('pagination::bootstrap-4') }}
  </div>
</div>

<x-modal-delete
  id="eliminar-asentamiento"
  :route="route('asentamiento.destroy')"
  message="Estás a punto de eliminar el siguiente asentamiento de forma permanente:" />
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
<script src="{{asset('dist/js/mensajes.js')}}"></script>
<script>
  $(function() {


  });
</script>
@endsection