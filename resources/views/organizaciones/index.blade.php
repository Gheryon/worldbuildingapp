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
    @if($tipos->count()>0)
    <option value="0">Todos</option>
    @foreach($tipos as $tipo)
    <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
    @endforeach
    @endif
  </select>
</li>
<li class="nav-item ml-2">
  <select id="order" class="form-control ml-2" name="order">
    <option selected disabled value="ASC">Orden</option>
    <option value="asc">Ascendente</option>
    <option value="desc">Descendente</option>
  </select>
</li>
@endsection

@section('navbar-search')
<li class="nav-item">
  <form class="form-inline ml-2" action="{{route('organizaciones.search')}}" method="GET">
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
  <h1>Instituciones</h1>
</div>
<hr>

<!-- Modal -->
<div class="modal fade" id="eliminar-organizacion" tabindex="-1" role="dialog" aria-labelledby="Confirmar eliminacion" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-danger">
        <div class="card-header">
          <h5 class="card-title" id="confirmar_eliminacion">Confirmar eliminación</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <div class="row">
            <p> ¿Borrar organizacion: <span id="nombre-borrar"> </span>?</p>
          </div>
        </div>
        <div class="card-footer">
          <form id="form-confirmar-borrar" class="col-md-auto" action="{{route('organizacion.destroy')}}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id_borrar" id="id_borrar">
            <input type="hidden" name="nombre_borrado" id="nombre_borrado">
            <button type="button" id="cancelar-borrar-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="confirmar-borrar-button" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  @if($organizaciones->count()>0)
  @foreach($organizaciones as $organizacion)
  <div class="col-sm-12 col-md-6 col-lg-3 col-xl-2">
    <div class="card card-dark card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <h2 class="lead"><b>{{$organizacion->nombre}}</b></h2>
          <img class="img-fluid" src="{{asset("storage/escudos/{$organizacion->escudo}")}}" alt="Escudo">
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
          <a href="{{route('organizacion.show',$organizacion->id_organizacion)}}" role="button" title="Ver" class="btn btn-info btn-sm col-4"><b><i class="fas fa-id-card mr-1"></i></b></a>
          <a href="{{route('organizacion.edit',$organizacion->id_organizacion)}}" role="button" title="Editar" class="btn btn-success btn-sm col-4"><b><i class="fas fa-pencil-alt mr-1"></i></b></a>
          <button data-id="{{$organizacion->id_organizacion}}" data-nombre="{{$organizacion->nombre}}" type="button" title="Borrar" class="borrar btn btn-danger btn-sm col-4" data-toggle="modal" data-target="#eliminar-organizacion"><i class="fas fa-trash mr-1"></i></button>
        </div>
      </div>
    </div>
  </div>
  @endforeach
  @else
  <div class="col-12">
    <h5 class="card-title">No hay instituciones almacenadas</h5>
  </div>
  </br>
  <div class="col-12 mt-3">
    <a href="{{route('organizacion.create')}}" class="btn btn-dark">Añadir organización nueva</a>
  </div>
  @endif
</div>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
<script src="{{asset('dist/js/mensajes.js')}}"></script>
<script>
  $(function() {

    $(document).on('change', '#order', function() {
      orden = this.value;
      tipo = "{{$tipo_o}}";
      let url = "{{ route('organizaciones.index', ['orden'=>'_orden', 'tipo'=>'_tipo']) }}";
      url = url.replace('_orden', orden);
      url = url.replace('_tipo', tipo);
      document.location.href = url;
    });

    $(document).on('change', '#filter_tipo', function() {
      tipo = this.value;
      orden = "{{$orden}}";
      let url = "{{ route('organizaciones.index', ['orden'=>'_orden', 'tipo'=>'_tipo']) }}";
      url = url.replace('_orden', orden);
      url = url.replace('_tipo', tipo);
      document.location.href = url;
    });
  });
</script>
@endsection