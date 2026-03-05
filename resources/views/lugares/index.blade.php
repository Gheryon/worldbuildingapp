@extends('layouts.index')

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
<x-order-input name="orden" label="Orden" :orden="$orden" />

<li class="nav-item ml-2">
  <a href="{{ route('lugares.index') }}" class="btn btn-outline-dark ml-2" title="Limpiar filtros">
    <i class="fas fa-sync-alt"></i>
  </a>
</li>
@endsection

@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('lugares.index')}}" method="GET">
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

<div class="row">
  @forelse($lugares as $lugar)
  <!--<x-lugar-card :lugar="$lugar" />-->
  <x-lugar-card-2 :lugar="$lugar" />
  
  @empty
  <div class="col-12 text-center mt-5">
    <div class="callout callout-info">
      <h5>No se encontraron lugares</h5>
      <p>Intenta ajustar los filtros o crea uno nuevo.</p>
      <a href="{{route('lugar.create')}}" class="btn btn-dark text-light">Crear nuevo lugar</a>
    </div>
  </div>
  @endforelse
</div>

<div class="row">
  <div class="col-12 d-flex justify-content-center mt-4">
    {{ $lugares->appends(request()->query())->links('pagination::bootstrap-4') }}
  </div>
</div>

<x-modal-delete
  id="eliminar-lugar"
  :route="route('lugar.destroy')"
  message="Estás a punto de eliminar el siguiente lugar de forma permanente:" />
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
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
      const baseUrl = "{{ route('lugares.index') }}";
      const urlFinal = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
      //console.log(orden, tipo, urlFinal);
      document.location.href = urlFinal;
    }

    $(document).on('change', '#order', redirigirConFiltros);
    $(document).on('change', '#filter_tipo', redirigirConFiltros);    
  });
</script>
@endsection