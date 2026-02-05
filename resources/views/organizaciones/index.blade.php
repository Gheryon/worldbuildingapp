@extends('layouts.index')

@section('title')
<title id="title">Instituciones</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('organizacion.create')}}" class="btn btn-dark">Nueva organización</a>
</li>

<li class="nav-item ml-2">
  <select id="filter_tipo" class="form-control ml-2" name="filter_tipo">
    <option selected disabled value="0">Filtrar tipo</option>
    <option value="0" {{ $tipo_id == 0 ? 'selected' : '' }}>Todos</option>
    @foreach($tipos_organizacion as $tipo)
    <option value="{{$tipo->id}}" {{ $tipo_id == $tipo->id ? 'selected' : '' }}>{{$tipo->nombre}}</option>
    @endforeach
  </select>
</li>

<x-order-input name="orden" label="Orden" :orden="$orden" />

<li class="nav-item ml-2">
  <a href="{{ route('organizaciones.index') }}" class="btn btn-outline-dark ml-2" title="Limpiar filtros">
    <i class="fas fa-sync-alt"></i>
  </a>
</li>
@endsection

@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('organizaciones.index')}}" method="GET">
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
  <h1 class="text-center mb-4">Instituciones</h1>
<hr>

<div class="row">
  @forelse($organizaciones as $organizacion)
  <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
    <div class="card card-dark card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <h2 class="lead"><b>{{$organizacion->nombre}}</b></h2>
          <img class="img-fluid" src="{{asset("storage/escudos/{$organizacion->escudo}")}}" alt="Escudo de {{ $organizacion->nombre }}">
        </div>
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Tipo:</b> {{$organizacion->tipo}}
          </li>
        </ul>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <div class="row text-right">
          <a href="{{route('organizacion.show',$organizacion->id)}}" role="button" title="Ver" class="btn btn-info btn-sm col-4"><b><i class="fas fa-id-card mr-1"></i></b></a>
          <a href="{{route('organizacion.edit',$organizacion->id)}}" role="button" title="Editar" class="btn btn-success btn-sm col-4"><b><i class="fas fa-pencil-alt mr-1"></i></b></a>
          <button data-id="{{$organizacion->id}}" data-nombre="{{$organizacion->nombre}}" type="button" title="Borrar" class="borrar btn btn-danger btn-sm col-4" data-toggle="modal" data-target="#eliminar-organizacion"><i class="fas fa-trash mr-1"></i></button>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12 text-center mt-5">
    <div class="callout callout-info">
      <h5>No se encontraron organizaciones</h5>
      <p>Intenta ajustar los filtros o crea una nueva.</p>
      <a href="{{route('organizacion.create')}}" class="btn btn-dark text-light">Crear nueva organización</a>
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
    :route="route('organizacion.destroy')" 
    message="Estás a punto de eliminar la siguiente organización de forma permanente:"
/>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
<script src="{{asset('dist/js/mensajes.js')}}"></script>
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