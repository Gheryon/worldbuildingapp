@extends('layouts.index')

@section('title')
<title id="title">Artículos</title>
@endsection

@section('navbar-buttons')
<li class="nav-item">
  <a href="{{route('articulos.create')}}" class="btn btn-dark">Nuevo articulo</a>
</li>
<li class="nav-item">
  <select id="filtro_tipo" class="form-control ml-2" name="filtro_tipo">
    <option selected disabled value="ASC">Filtro</option>
    <option value="all" {{ $tipo == "all" ? 'selected' : '' }}>Todos</option>
    <option value="Referencia" {{ $tipo == "Referencia" ? 'selected' : '' }}>Referencia</option>
    <option value="Canon" {{ $tipo == "Canon" ? 'selected' : '' }}>Canon</option>
  </select>
</li>
<x-order-input name="orden" label="Orden A-Z" :orden="$orden" />

<x-order-date-input :fecha="$fecha" />

<li class="nav-item ml-2">
  <a href="{{ route('articulos.index') }}" class="btn btn-outline-dark ml-2" title="Limpiar filtros">
    <i class="fas fa-sync-alt"></i>
  </a>
</li>
@endsection

@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('articulos.index')}}" method="GET" value="{{ request('search') }}>
    <div class=" input-group">
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
<table class="table table-bordered table-striped table-hover">
  <thead>
    <tr>
      <th>Nombre</th>
      <th style="width: 100px;">Tipo</th>
      <th style="width: 200px;">Última modificación</th>
      <th class="text-center" style="width: 150px;">Acciones</th>
    </tr>
  </thead>
  <tbody>
    @forelse($articulos as $articulo)
    <tr>
      <td class="align-middle">{{ $articulo->nombre }}</td>
      <td class="align-middle">
        <span class="badge {{ $articulo->tipo === 'Canon' ? 'badge-primary' : 'badge-secondary' }}">
          {{ $articulo->tipo }}
        </span>
      </td>
      <td class="align-middle text-muted small">
        {{ $articulo->updated_at->diffForHumans() }} 
      </td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <a href="{{ route('articulos.show', $articulo->id) }}" class="btn btn-sm btn-info" title="Ver">
            <i class="fas fa-eye"></i>
          </a>
          <a href="{{ route('articulos.edit', $articulo->id) }}" class="btn btn-sm btn-success" title="Editar">
            <i class="fas fa-pencil-alt"></i>
          </a>
          <button data-id="{{ $articulo->id }}" data-nombre="{{ $articulo->nombre }}"
            class="borrar btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminar-articulo" title="Borrar">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="3" class="text-center py-4">
        <p class="text-muted mb-3">No hay artículos almacenados</p>
        <a href="{{ route('articulos.create') }}" class="btn btn-dark btn-sm">Añadir nuevo artículo</a>
      </td>
    </tr>
    @endforelse
  </tbody>
</table>

<x-modal-delete
  id="eliminar-articulo"
  :route="route('articulos.destroy')"
  message="Estás a punto de eliminar el siguiente articulo de forma permanente:" />
@endsection

@section('specific-scripts')
<!-- articulos javascript -->
<script src="{{asset('dist/js/config.js')}}"></script>
<script>
  $(function() {

    function redirigirConFiltros() {
      const orden = $('#order').val();
      const tipo = $('#filtro_tipo').val();
      const fecha = $('#filtro_fecha').val();
      const search = $('input[name="search"]').val();

      // Creamos el objeto de parámetros de búsqueda
      const params = new URLSearchParams();

      // Solo agregamos los parámetros si tienen un valor útil
      if (orden) params.append('orden', orden);
      if (tipo && tipo !== 'all') params.append('tipo', tipo);
      if (fecha) params.append('fecha', fecha);
      if (search) params.append('search', search); //Mantiene la búsqueda al filtrar

      // Generamos la URL base desde Laravel
      const baseUrl = "{{ route('articulos.index') }}";
      const urlFinal = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
      //console.log(orden, tipo, urlFinal);
      document.location.href = urlFinal;
    }

    $(document).on('change', '#order, #filtro_tipo, #filtro_fecha', redirigirConFiltros);
  });
</script>
@endsection