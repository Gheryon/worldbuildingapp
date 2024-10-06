@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Galería de imágenes</title>
@endsection

@section('navbar-buttons')
<button type="button" title="Subir imagen" class="nuevo btn btn-dark btn-sm" data-toggle="modal" data-target="#subir-imagen">Subir imagen</button>
@endsection

@section('content')
<div class="row">
  <h1>Galería de imágenes</h1>
</div>
<hr>

<!-- Modal -->
<div class="modal fade" id="confirmar_eliminacion" tabindex="-1" role="dialog" aria-labelledby="Confirmar eliminacion" aria-hidden="true">
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
            <p> ¿Borrar tipo: <span id="texto-borrar"> </span>?</p>
          </div>
        </div>
        <div class="card-footer">
          <form id="form-confirmar-borrar" class="col-md-auto" action="{{route('config.destroy')}}" method="POST">
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

<div class="modal fade" id="subir-imagen" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="Editar nombre" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-dark">
        <div class="card-header">
          <h5 class="card-title" id="subirImagenLabel">Subir</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <form action="{{route('galeria.store')}}" method="post" enctype="multipart/form-data">
            @csrf

            <input id="path" type="hidden" name="path" value="0">
            <input id="owner" type="hidden" name="owner" value="0">
            <input id="table_owner" type="hidden" name="table_owner" value="0">

            <div class="form-group">
              <label for="nombre">Imagen nombre:</label>
              <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
              <label for="image">Seleccionar imagen:</label>
              <input type="file" class="form-control" id="imagen" name="imagen" required>
            </div>

        </div>
        <div class="card-footer">
          <button type="button" id="cancelar-editar-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
          <button type="submit" id="submit-editar-button" class="btn btn-success">Guardar</button>
          <button type="button" id="cerrar-editar-button" class="btn btn-primary" data-dismiss="modal" style="display:none">Cerrar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      @foreach($imagenes as $image)
      <div class="col-md-4">
        <div class="thumbnail">
          <!-- Thumbnail Image -->
          <img src="{{ asset('storage/imagenes/' . $image->nombre) }}" alt="{{ $image->nombre }}" style="width:100%; cursor:pointer;" data-toggle="modal" data-target="#imageModal{{ $image->id }}">

          <!-- Modal -->
          <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel{{ $image->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="imageModalLabel{{ $image->id }}">{{ $image->nombre }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                  <!-- Full Size Image -->
                  <img src="{{ asset('storage/imagenes/' . $image->nombre) }}" alt="{{ $image->nombre }}" style="width:100%;">
                </div>
              </div>
            </div>
          </div>
          <!-- End of Modal -->
        </div>
      </div>
      @endforeach
    </div>
  </div> <!-- /container -->
</section>
<!-- /.content -->

@endsection

@section('specific-scripts')
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