@extends('layouts.index')

@section('title')
<title id="title">Editar {{$articulo->nombre}}</title>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <form id="form-edit" action="{{route('articulos.update', $articulo->id)}}" method="post">
    @csrf
    @method('PUT')

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3">Editar {{$articulo->nombre}}</h1>
      <div>
        <a href="{{route('articulos.index')}}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" class="btn btn-dark shadow-sm" id="guardar">
          <i class="fas fa-save mr-1"></i> Guardar cambios
        </button>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <div class="row">
          <div class="col-md-8 mb-3">
            <label for="nombre" class="form-label fw-bold">Nombre del artículo</label>
            <input type="text" name="nombre"
              class="form-control @error('nombre') is-invalid @enderror"
              id="nombre" value="{{ old('nombre', $articulo->nombre) }}"
              placeholder="Ej: El Imperio de Eldoria" required>
            @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-4 mb-3">
            <label for="tipo" class="form-label fw-bold">Tipo de contenido</label>
            <select class="form-control @error('tipo') is-invalid @enderror" name="tipo" id="tipo" required>
              <option selected disabled value="">Elegir...</option>
              <option value="Referencia" {{ old('tipo', $articulo->tipo) == 'Referencia' ? 'selected' : '' }}>Referencia</option>
              <option value="Canon" {{ old('tipo', $articulo->tipo) == 'Canon' ? 'selected' : '' }}>Canon</option>
            </select>
            @error('tipo')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <label for="contenido" class="form-label fw-bold">Contenido</label>
            <textarea class="form-control summernote" id="contenido" name="contenido" required>{{ old('contenido', $articulo->contenido) }}</textarea>
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
<script src="{{asset('dist/js/common.js')}}"></script>
<script>
  $(document).ready(function() {
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