@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Religiones</title>
@endsection

@section('navbar-buttons')
<a href="{{route('religion.create')}}" class="btn btn-dark">Nueva religion</a>
@endsection

@section('content')
<div class="row">
  <h1>Religiones</h1>
</div>
<hr>

<!-- Modal -->
<div class="modal fade" id="eliminar-religion" tabindex="-1" role="dialog" aria-labelledby="Confirmar eliminacion" aria-hidden="true">
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
            <p> ¿Borrar religion: <span id="nombre-borrar"> </span>?</p>
          </div>
        </div>
        <div class="card-footer">
          <form id="form-confirmar-borrar" class="col-md-auto" action="{{route('religion.destroy')}}" method="POST">
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
@if (Arr::has($religiones, 'error.error'))
<div class="text-center">No se encontraron religiones.
{{Arr::get($religiones, 'error.error')}}</div>
@else
  @foreach($religiones as $religion)
  <div class="col-6 col-sm-6 col-md-6 col-lg-6">
    <div class="card card-dark card-outline">
      <div class="card-body box-profile">
        <h3 class="profile-username text-center">{{$religion->nombre}}</h3>
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Descripción breve: </b>
          </li>
        </ul>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <div class="row text-right">
          <a href="{{route('religion.show',$religion->id)}}" type="button" title="Ver" class="btn btn-info btn-sm col-4"><b><i class="fas fa-id-card mr-1"></i></b></a>
          <a href="{{route('religion.edit',$religion->id)}}" type="button" title="Editar" class="btn btn-success btn-sm col-4"><b><i class="fas fa-pencil-alt mr-1"></i></b></a>
          <button id="{{$religion->id}}" nombre="{{$religion->nombre}}" type="button" title="Borrar" class="borrar btn btn-danger btn-sm col-4" data-toggle="modal" data-target="#eliminar-religion"><i class="fas fa-trash mr-1"></i></button>
        </div>
      </div>
    </div>
  </div>
  @endforeach
@endif

</div>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
<script>
  @if(Session::has('message'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 600,
    "preventDuplicates": true,
  }
  toastr.success("{{ session('message') }}");
  @endif

  @if(Session::has('error'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 900,
    "preventDuplicates": true,
  }
  toastr.error("{{ session('error') }}");
  @endif

  @if(Session::has('info'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 600,
    "preventDuplicates": true,
  }
  toastr.info("{{ session('info') }}");
  @endif

  @if(Session::has('warning'))
  toastr.options = {
    "closeButton": true,
    "closeOnHover": true,
    "progressBar": false,
    "showDuration": 600,
    "preventDuplicates": true,
  }
  toastr.warning("{{ session('warning') }}");
  @endif
</script>
@endsection