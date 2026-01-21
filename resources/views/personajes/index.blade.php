@extends('layouts.index')

@section('title')
<title id="title">Personajes</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('personaje.create')}}" class="btn btn-dark">Nuevo personaje</a>
</li>
<li class="nav-item ml-2">
  <select id="filter_especie" class="form-control ml-2" name="filter_especie">
    <option selected disabled value="0">Filtrar especie</option>
    <option value="0" {{ $especie_id == 0 ? 'selected' : '' }}>Todas</option>
    @foreach($especies as $especie)
    <option value="{{$especie->id}}" {{ $especie_id == $especie->id ? 'selected' : '' }}>{{$especie->nombre}}</option>
    @endforeach
  </select>
</li>
<li class="nav-item ml-2">
  <select id="order" class="form-control ml-2" name="order">
    <option disabled value="ASC">Orden</option>
    <option value="asc" {{ $orden == 'asc' ? 'selected' : '' }}>Ascendente</option>
    <option value="desc" {{ $orden == 'desc' ? 'selected' : '' }}>Descendente</option>
  </select>
</li>

<li class="nav-item ml-2">
  <a href="{{ route('personajes.index') }}" class="btn btn-outline-light ml-2" title="Limpiar filtros">
    <i class="fas fa-sync-alt"></i>
  </a>
</li>
@endsection

@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('personajes.index')}}" method="GET">
    <div class="input-group">
      <input type="search" name="search" class="form-control" placeholder="Nombre a buscar" value="{{ request('search') }}">
      <input type="hidden" name="especie" value="{{ request('especie', 0) }}">
      <input type="hidden" name="orden" value="{{ request('orden', 'asc') }}">
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
<h1 class="text-center mb-4">Personajes</h1>

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
  @forelse($personajes as $personaje)
  <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2 mb-3">
    <div class="card card-dark card-outline h-100">
      <div class="card-body box-profile">
        <div class="text-center">
          <img class="profile-user-img img-fluid img-circle" src="{{ asset("storage/retratos/" . ($personaje->retrato ?? 'default.png')) }}" alt="Retrato de {{ $personaje->nombre }}">
        </div>
        <h3 class="profile-username text-center text-truncate">{{ $personaje->nombre }}</h3>
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b><i class="fa-solid fa-dna mr-1"></i> Especie</b>
            <span class="float-right text-muted">{{ $personaje->especie }}</span>
          </li>
          <li class="list-group-item">
            <b><i class="fas fa-venus-mars mr-1"></i> Sexo</b>
            <span class="float-right text-muted">{{ $personaje->sexo }}</span>
          </li>
        </ul>
      </div>

      <div class="card-footer">
        <div class="btn-group w-100" role="group">
          <a href="{{ route('personaje.show', $personaje->id) }}" class="btn btn-info btn-sm" title="Ver">
            <i class="fas fa-id-card"></i>
          </a>
          <a href="{{ route('personaje.edit', $personaje->id) }}" class="btn btn-success btn-sm" title="Editar">
            <i class="fas fa-pencil-alt"></i>
          </a>
          <button type="button" class="btn btn-danger btn-sm borrar" data-id="{{ $personaje->id }}" data-nombre="{{ $personaje->nombre }}" data-toggle="modal" data-target="#eliminar-personaje">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12 text-center mt-5">
    <div class="callout callout-info">
      <h5>No se encontraron personajes</h5>
      <p>Intenta ajustar los filtros o crea uno nuevo.</p>
      <a href="{{route('personaje.create')}}" class="btn btn-dark text-light">Crear nuevo personaje</a>
    </div>
  </div>
  @endforelse
</div>

<div class="row">
  <div class="col-12 d-flex justify-content-center mt-4">
    {{ $personajes->appends(request()->query())->links('pagination::bootstrap-4') }}
  </div>
</div>


@endsection

@section('specific-scripts')
<!-- articulos javascript -->
<script src="{{asset('dist/js/config.js')}}"></script>
<script>
  $(function() {

    function redirigirConFiltros() {
      const orden = $('#order').val();
      const especie = $('#filter_especie').val();
      const search = $('input[name="search"]').val();

      // Creamos el objeto de parámetros de búsqueda
      const params = new URLSearchParams();

      // Solo agregamos los parámetros si tienen un valor útil
      if (orden) params.append('orden', orden);
      if (especie && especie !== '0') params.append('especie', especie);
      if (search) params.append('search', search); //Mantiene la búsqueda al filtrar

      // Generamos la URL base desde Laravel
      const baseUrl = "{{ route('personajes.index') }}";
      const urlFinal = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
      //console.log(orden, tipo, urlFinal);
      document.location.href = urlFinal;
    }

    $(document).on('change', '#order', redirigirConFiltros);
    $(document).on('change', '#filter_especie', redirigirConFiltros);
  });
</script>
@endsection