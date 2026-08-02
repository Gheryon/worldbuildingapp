@extends('layouts.index')

@section('title')
<title id="title">Instituciones</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('organizaciones.create')}}" class="btn btn-dark">Nueva organización</a>
</li>

<x-filter-tipo-select
  :tipos="$tipos_organizacion"
  :tipo-id="$tipo_id"
  name="filter_tipo"
  label="Filtrar tipo"
  placeholder="Filtrar tipo"
  todos-label="Todos" />

<x-order-input name="orden" label="Orden" :orden="$orden" />

<li class="nav-item ml-2">
  <a href="{{ route('organizaciones.index') }}" class="btn btn-outline-dark ml-2" title="Limpiar filtros">
    <i class="fas fa-sync-alt"></i>
  </a>
</li>
@endsection

@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('organizaciones.index')}}" method="GET" role="search" aria-label="Buscar organizaciones">
    <div class="input-group">
      <label for="search" class="sr-only">Buscar organización</label>
      <input type="search" id="search" name="search" class="form-control" placeholder="Nombre a buscar" aria-label="Buscar organización por nombre">
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
  @forelse($organizaciones as $organizacion)
  <x-organizacion-card :organizacion="$organizacion" />
  @empty
  <div class="col-12 text-center mt-5">
    <div class="callout callout-info">
      <h2>No se encontraron organizaciones</h2>
      <p>Intenta ajustar los filtros o crea una nueva.</p>
      <a href="{{route('organizaciones.create')}}" class="btn btn-dark text-light">Crear nueva organización</a>
    </div>
  </div>
  @endforelse
</div>

<div class="row">
  <div class="col-12 d-flex justify-content-center mt-4">
    {{ $organizaciones->appends(request()->query())->links('pagination::bootstrap-4') }}
  </div>
</div>

<x-modal-delete 
    id="eliminar-organizacion" 
    message="Estás a punto de eliminar la siguiente organización de forma permanente:"
/>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
<script>
  $(function() {

    function redirigirConFiltros() {
      const orden = $('#order').val();
      const tipo = $('#filter_tipo').val();
      const search = $('input[name="search"]').val();

      // Creamos el objeto de parámetros de búsqueda
      const params = new URLSearchParams();

      // Solo agregamos los parámetros si tienen un valor útil
      if (orden) params.append('orden', orden);
      if (tipo && tipo !== '0') params.append('tipo', tipo);
      if (search) params.append('search', search); //Mantiene la búsqueda al filtrar

      // Generamos la URL base desde Laravel
      const baseUrl = "{{ route('organizaciones.index') }}";
      const urlFinal = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
      //console.log(orden, tipo, urlFinal);
      document.location.href = urlFinal;
    }

    $(document).on('change', '#order', redirigirConFiltros);
    $(document).on('change', '#filter_tipo', redirigirConFiltros);
  });
</script>
@endsection