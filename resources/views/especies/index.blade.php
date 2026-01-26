@extends('layouts.index')

@section('title')
<title id="title">Especies</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('especie.create')}}" class="btn btn-dark">Nueva especie</a>
</li>
<li class="nav-item ml-2">
<select id="order" class="form-control ml-2" name="order">
  <option selected disabled value="ASC">Orden</option>
  <option value="asc">Ascendente</option>
  <option value="desc">Descendente</option>
</select>
</li>
@endsection

@section('content')
<div class="row">
  <h1>Especies</h1>
</div>
<hr>

<!-- Modal -->
<div class="modal fade" id="eliminar-especie" tabindex="-1" role="dialog" aria-labelledby="confirmar_eliminacion" aria-hidden="true">
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
            <p> ¿Borrar especie: <span id="nombre-borrar"> </span>?</p>
          </div>
        </div>
        <div class="card-footer">
          <form id="form-confirmar-borrar" class="col-md-auto" action="{{route('especie.destroy')}}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id_borrar" id="id_borrar">
            <input type="hidden" name="tipo" id="tipo">
            <input type="hidden" name="nombre_borrado" id="nombre_borrado">
            <button type="button" id="cancelar-borrar-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="confirmar-borrar-button" class="btn btn-danger">Eliminar</button>
            <button type="button" id="cerrar-borrar-button" class="btn btn-primary" data-dismiss="modal" style="display:none">Cerrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
@if (Arr::has($especies, 'error.error'))
  {{Arr::get($especies, 'error.error')}}
@else
  @if($especies->count()>0)
    @foreach($especies as $especie)
    <div class="col-4 col-sm-6 col-md-4 col-lg-3">
      <div class="card card-dark card-outline">
        <div class="card-body box-profile">
          <h3 class="profile-username text-center">{{$especie->nombre}}</h3>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <div class="row text-right">
            <a href="{{route('especie.show',$especie->id)}}" role="button" title="Ver" class="btn btn-info btn-sm col-4"><b><i class="fas fa-id-card mr-1"></i></b></a>
            <a href="{{route('especie.edit',$especie->id)}}" role="button" title="Editar" class="btn btn-success btn-sm col-4"><b><i class="fas fa-pencil-alt mr-1"></i></b></a>
            <button data-id="{{$especie->id}}" data-nombre="{{$especie->nombre}}" type="button" title="Borrar" class="borrar btn btn-danger btn-sm col-4" data-toggle="modal" data-target="#eliminar-especie"><i class="fas fa-trash mr-1"></i></button>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  @else
  <div class="col-12">
    <h5 class="card-title">No hay especies almacenadas</h5>
  </div>
    </br>
  <div class="col-12 mt-3">
    <a href="{{route('especie.create')}}" class="btn btn-dark">Añadir nueva especie</a>
  </div>
  @endif
@endif
</div>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
<script>
  $(function() {

    $(document).on('change', '#order', function(){
      orden=this.value;
      let url = "{{ route('especies.index', ['orden'=>'_orden']) }}";
      url = url.replace('_orden', orden);
      document.location.href=url;
    });
  });
</script>
@endsection