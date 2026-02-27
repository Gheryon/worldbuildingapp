@extends('layouts.index')

@section('title')
<title id="title">Nuevo relato</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('relatos.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <form id="form-create" action="{{route('relatos.store')}}" method="post">
    @csrf

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3">Crear nuevo relato</h1>
      <div>
        <a href="{{route('relatos.index')}}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" class="btn btn-dark shadow-sm" id="guardar">
          <i class="fas fa-save mr-1"></i> Guardar
        </button>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <div class="row">
          <div class="col-md-8 mb-3">
            <label for="nombre" class="form-label fw-bold">Nombre</label>
            <input type="text" name="nombre"
              class="form-control @error('nombre') is-invalid @enderror"
              id="nombre" value="{{ old('nombre') }}"
              placeholder="Ej: El Imperio de Eldoria" required>
            @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-4 mb-3">
            <label for="personajes" class="form-label">Personajes relevantes</label>
            <select class="form-select form-control" multiple="multiple" data-placeholder="Personajes" name="personajes[]" id="personajes" style="width: 100%;">
              @foreach($personajes as $id => $nombre)
              <option value="{{$id}}">{{$nombre}}</option>
              @endforeach
            </select>
            @error('personajes')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <label for="contenido" class="form-label fw-bold">Contenido</label>
            <textarea class="form-control summernote" id="contenido" name="contenido" required>{{ old('contenido') }}</textarea>
            @error('contenido')
            <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

@endsection

@section('specific-scripts')
<!--<script src="../js/summernote-bs4.min.js"></script>-->
<script src="{{asset('dist/js/common.js')}}"></script>

<script>
  $(document).ready(function() {
    $('#personajes').select2({
      theme: 'bootstrap4', // Importante, pues la version bootstrap actual es la 4
      //placeholder: $('#personajes').data('placeholder'),
      allowClear: true,
      width: '100%',
      containerCssClass: ':all:'
    });

    // Prevención de pérdida de datos
    let formChanged = false;
    $('#form-create').on('change', 'input, select, textarea', function() {
      formChanged = true;
    });

    $(window).on('beforeunload', function() {
      if (formChanged) {
        return "Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?";
      }
    });

    $('#form-create').on('submit', function() {
      $(window).off('beforeunload'); // Desactivar alerta al enviar el formulario
    });
  });
</script>
@endsection