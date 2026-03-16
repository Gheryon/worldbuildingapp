@extends('layouts.index')

@section('title')
<title id="title">Conflictos</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('conflicto.create')}}" class="btn btn-dark"><i class="fas fa-plus-circle mr-1"></i> Nuevo conflicto</a>
</li>
<li class="nav-item ml-2">
  <select id="filter_tipo" class="form-control ml-2" name="filter_tipo">
    <option disabled {{ is_null($tipo) || $tipo == 0 ? 'selected' : '' }}>Filtrar tipo</option>
    <option value="0" {{ $tipo === '0' || $tipo === 0 ? 'selected' : '' }}>Todos</option>
    @foreach($tipos as $item)
    <option value="{{$item->id}}" {{ $item->id == $tipo ? 'selected' : '' }}>{{$item->nombre}}</option>
    @endforeach
  </select>
</li>
<li class="nav-item ml-2">
  <select id="filter_magia" class="form-control ml-2" name="filter_magia">
    <option disabled {{ is_null($magia) || $magia == 0 ? 'selected' : '' }}>Filtrar magia</option>
    <option value="0" {{ $magia == '0' ? 'selected' : '' }}>Todos</option>
    <option value="1" {{ $magia == '1' ? 'selected' : '' }}>Sí</option>
    <option value="2" {{ $magia == '2' ? 'selected' : '' }}>No</option>
  </select>
</li>
<x-order-input name="orden" label="Orden" :orden="$orden" />

<li class="nav-item ml-2">
  <a href="{{ route('conflictos.index') }}" class="btn btn-outline-dark ml-2" title="Limpiar filtros">
    <i class="fas fa-sync-alt"></i>
  </a>
</li>
@endsection

@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('conflictos.index')}}" method="GET">
    <div class="input-group">
      <input type="search" name="search" class="form-control shadow-sm" placeholder="Buscar" value="{{ request('search') }}">
      <div class="input-group-append">
        <button type="submit" class="btn btn-default shadow-sm">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </div>
  </form>
</li>
@endsection

@section('content')
<div class="row">
  <h1>Conflictos y batallas</h1>
</div>
<hr>

<div class="row">
  @forelse($conflictos as $conflicto)
  <x-conflicto-card :conflicto="$conflicto" />

 
  @empty
  <div class="col-12 text-center mt-5">
    <div class="callout callout-info">
      <h5>No se encontraron conflictos</h5>
      <p>Intenta ajustar los filtros o crea uno nuevo.</p>
      <a href="{{route('conflicto.create')}}" class="btn btn-dark text-light">Crear nuevo conflicto</a>
    </div>
  </div>
  @endforelse
</div>

<div class="row">
  <div class="col-12 d-flex justify-content-center mt-4">
    {{ $conflictos->appends(request()->query())->links('pagination::bootstrap-4') }}
  </div>
</div>

<x-modal-delete
  id="eliminar-conflicto"
  :route="route('conflicto.destroy')"
  message="Estás a punto de eliminar el siguiente conflicto de forma permanente:" />
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
<script>
 $(function() {

    function redirigirConFiltros() {
      const orden = $('#order').val();
      const tipo = $('#filter_tipo').val();
      const magia = $('#filter_magia').val();
      const search = $('input[name="search"]').val();

      // Creamos el objeto de parámetros de búsqueda
      const params = new URLSearchParams();

      // Solo agregamos los parámetros si tienen un valor útil
      if (orden) params.append('orden', orden);
      if (tipo && tipo !== '0') params.append('tipo', tipo);
      if (magia && magia !== '0') params.append('magia', magia);
      if (search) params.append('search', search); //Mantiene la búsqueda al filtrar

      // Generamos la URL base desde Laravel
      const baseUrl = "{{ route('conflictos.index') }}";
      const urlFinal = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
      //console.log(orden, tipo, urlFinal);
      document.location.href = urlFinal;
    }

    $(document).on('change', '#order', redirigirConFiltros);
    $(document).on('change', '#filter_tipo', redirigirConFiltros);
    $(document).on('change', '#filter_magia', redirigirConFiltros);
  });
</script>
@endsection