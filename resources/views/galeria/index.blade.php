@extends('layouts.index')

@section('title')
<title id="title">Galería de imágenes</title>
@endsection

@section('navbar-buttons')
<button type="button" title="Subir imagen" class="nuevo btn btn-dark btn-sm" data-toggle="modal" data-target="#subir-imagen">Subir imagen</button>
@endsection

@php
function display_name($image) {
if ($image->owner_name) {
return $image->owner_name;
}
$name = pathinfo($image->nombre, PATHINFO_FILENAME);
return preg_replace('/_\d+$/', '', $name);
}
@endphp

@section('content')
<div class="row metamorphous-regular">
  <h1>Galería de imágenes</h1>
</div>
<hr>

<!-- Modal subir imagen -->
<div class="modal fade" id="subir-imagen" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="card card-dark">
        <div class="card-header">
          <h5 class="card-title">Subir imagen</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <form action="{{route('galeria.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-8">
                <div class="form-group">
                  <label for="nombre">Nombre de la imagen:</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                  <label for="imagen">Seleccionar imagen:</label>
                  <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                </div>
              </div>
              <div class="col-4">
                <img id="imagen-preview" src="{{asset('storage/retratos/default.png')}}" class="img-fluid" style="width: 200px; height: 200px; object-fit: cover;">
              </div>
            </div>
        </div>
        <div class="card-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal renombrar -->
<div class="modal fade" id="renombrar-imagen" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-dark">
        <div class="card-header">
          <h5 class="card-title">Renombrar imagen</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <form id="form-renombrar" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label for="nuevo-nombre">Nuevo nombre:</label>
              <input type="text" class="form-control" id="nuevo-nombre" name="nombre" required>
            </div>
        </div>
        <div class="card-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Guardar</button>
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
      <div class="col-md-3">
        <div class="thumbnail" style="position:relative;">
          <!-- Thumbnail Image -->
          <img src="{{ asset('storage/imagenes/' . $image->nombre) }}" alt="{{ display_name($image) }}" style="width:100%; cursor:pointer;" data-toggle="modal" data-target="#imageModal{{ $image->id }}">

          <!-- Botones de acción solo para imágenes de galería -->
          @if($image->table_owner === 'galeria')
          <div style="position:absolute; top:5px; right:5px;">
            <button class="btn btn-sm btn-warning btn-renombrar" data-id="{{ $image->id }}" data-nombre="{{ display_name($image) }}" title="Renombrar">
              <i class="fas fa-edit"></i>
            </button>
            <button class="borrar btn btn-sm btn-danger btn-borrar" data-id="{{ $image->id }}" data-nombre="{{ display_name($image) }}" data-url="{{ route('galeria.destroy', $image->id) }}" title="Eliminar" data-toggle="modal" data-target="#eliminar-imagen">
              <i class="fas fa-trash"></i>
            </button>
          </div>
          @endif

          <!-- Modal imagen completa -->
          <div class="modal fade" id="imageModal{{ $image->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">{{ display_name($image) }}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <img src="{{ asset('storage/imagenes/' . $image->nombre) }}" alt="{{ $image->nombre }}" style="width:100%;">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<x-modal-delete
  id="eliminar-imagen"
  message="Estás a punto de eliminar la siguiente imagen de forma permanente:" />
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
<script>
  $(function() {
    // Renombrar imagen
    $(document).on('click', '.btn-renombrar', function() {
      var id = $(this).data('id');
      var nombre = $(this).data('nombre');
      $('#nuevo-nombre').val(nombre);
      $('#form-renombrar').attr('action', '{{ url("galeria") }}/' + id);
      $('#renombrar-imagen').modal('show');
    });

    //Previsualizar la imagen subida en el modal
    $('#imagen').change(function() {
      const file = this.files[0];
      console.log(file);
      if (file) {
        $('#imagen-preview').attr('src', URL.createObjectURL(file));
      }
    });

    // Resetear formulario y preview al cerrar modal de subir imagen
    $('#subir-imagen').on('hidden.bs.modal', function() {
      $(this).find('form')[0].reset();
      $('#imagen-preview').attr('src', '{{ asset("storage/retratos/default.png") }}');
    });

    // Resetear formulario al cerrar modal de renombrar
    $('#renombrar-imagen').on('hidden.bs.modal', function() {
      $(this).find('form')[0].reset();
    });
  });
</script>
@endsection