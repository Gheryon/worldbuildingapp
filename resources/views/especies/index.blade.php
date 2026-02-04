@extends('layouts.index')

@section('title')
<title id="title">Especies</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('especie.create')}}" class="btn btn-dark">Nueva especie</a>
</li>

<x-order-input name="orden" label="Orden" :orden="$orden" />

<li class="nav-item ml-2">
  <a href="{{ route('especies.index') }}" class="btn btn-outline-dark ml-2" title="Limpiar filtros">
    <i class="fas fa-sync-alt"></i>
  </a>
</li>
@endsection


@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('especies.index')}}" method="GET">
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
<div class="row">
  <h1>Especies</h1>
</div>
<hr>

<div class="row">
  @forelse($especies as $especie)
  <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
    <x-especie-card :especie="$especie" />
  </div>
  @empty
  <div class="col-12 text-center mt-5">
    <div class="callout callout-info">
      <h5>No se encontraron especies</h5>
      <p>Intenta ajustar los filtros o crea una nueva.</p>
      <a href="{{route('especie.create')}}" class="btn btn-dark text-light">Crear nueva especie</a>
    </div>
  </div>
  @endforelse
</div>

<div class="row">
  <div class="col-12 d-flex justify-content-center mt-4">
    {{ $especies->appends(request()->query())->links('pagination::bootstrap-4') }}
  </div>
</div>

<x-modal-delete 
    id="eliminar-especie" 
    :route="route('especie.destroy')" 
    message="Estás a punto de eliminar la siguiente especie de forma permanente:"
/>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
<script>
  $(function() {

    function redirigirConFiltros() {
      const orden = $('#order').val();
      const search = $('input[name="search"]').val();

      // Creamos el objeto de parámetros de búsqueda
      const params = new URLSearchParams();

      // Solo agregamos los parámetros si tienen un valor útil
      if (orden) params.append('orden', orden);
      if (search) params.append('search', search); //Mantiene la búsqueda al filtrar

      // Generamos la URL base desde Laravel
      const baseUrl = "{{ route('especies.index') }}";
      const urlFinal = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
      document.location.href = urlFinal;
    }

    $(document).on('change', '#order', redirigirConFiltros);
  });
</script>
@endsection