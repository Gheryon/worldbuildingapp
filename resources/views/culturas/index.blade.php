@extends('layouts.index')

@section('title')
<title id="title">Culturas</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('culturas.create')}}" class="btn btn-dark">Nueva cultura</a>
</li>

<li class="nav-item ml-2">
  <label for="filter_estatus" class="sr-only">Filtrar estatus</label>
  <select id="filter_estatus" class="form-control ml-2" name="filter_estatus" aria-label="Filtrar por estatus">
    <option selected disabled value="">Filtrar estatus</option>
    <option value="0" {{ $estatus_id == '0' ? 'selected' : '' }}>Todos</option>
    <option value="activa" {{ $estatus_id == 'activa' ? 'selected' : '' }}>Activa</option>
    <option value="extinta" {{ $estatus_id == 'extinta' ? 'selected' : '' }}>Extinta</option>
    <option value="en declive" {{ $estatus_id == 'en declive' ? 'selected' : '' }}>En declive</option>
    <option value="asimilada" {{ $estatus_id == 'asimilada' ? 'selected' : '' }}>Asimilada</option>
    <option value="en transicion" {{ $estatus_id == 'en transicion' ? 'selected' : '' }}>En transición</option>
  </select>
</li>

<x-order-input name="orden" label="Orden" :orden="$orden" />

<li class="nav-item ml-2">
  <a href="{{ route('culturas.index') }}" class="btn btn-outline-dark ml-2" title="Limpiar filtros">
    <i class="fas fa-sync-alt"></i>
  </a>
</li>
@endsection

@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('culturas.index')}}" method="GET" role="search" aria-label="Buscar culturas">
    <div class="input-group">
      <label for="search" class="sr-only">Buscar cultura</label>
      <input type="search" id="search" name="search" class="form-control" placeholder="Nombre a buscar" aria-label="Buscar cultura por nombre">
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
  @forelse($culturas as $cultura)
  <x-cultura-card :cultura="$cultura" />
  @empty
  <div class="col-12 text-center mt-5">
    <div class="callout callout-info">
      <h2>No se encontraron culturas</h2>
      <p>Intenta ajustar los filtros o crea una nueva.</p>
      <a href="{{route('culturas.create')}}" class="btn btn-dark text-light">Crear nueva cultura</a>
    </div>
  </div>
  @endforelse
</div>

<div class="row">
  <div class="col-12 d-flex justify-content-center mt-4">
    {{ $culturas->appends(request()->query())->links('pagination::bootstrap-4') }}
  </div>
</div>

<x-modal-delete 
    id="eliminar-cultura" 
    message="Estás a punto de eliminar la siguiente cultura de forma permanente:"
/>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
<script>
  $(function() {

    function redirigirConFiltros() {
      const orden = $('#order').val();
      const estatus = $('#filter_estatus').val();
      const search = $('input[name="search"]').val();
      const params = new URLSearchParams();
      if (orden) params.append('orden', orden);
      if (estatus && estatus !== '0') params.append('estatus', estatus);
      if (search) params.append('search', search);
      const baseUrl = "{{ route('culturas.index') }}";
      const urlFinal = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
      document.location.href = urlFinal;
    }

    $(document).on('change', '#order', redirigirConFiltros);
    $(document).on('change', '#filter_estatus', redirigirConFiltros);
  });
</script>
@endsection
