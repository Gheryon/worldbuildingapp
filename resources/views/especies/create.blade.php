@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Nueva especie</title>
@endsection

@section('navbar-buttons')
<a href="{{route('lugares.index')}}" class="btn btn-dark">Cancelar</a>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Nueva especie</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create-especie" class="position-relative needs-validation" action="{{route('especie.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row justify-content-md-center">
      <div class="col-md-auto form-actions">
        <button type="submit" id="submit-crear-button" class="btn btn-success">Guardar</button>
      </div>
    </div>
    <div class="row mt-3 mb-3 justify-content-md-center border">
      <div class="col">
        <div class="row mt-2">
          <div class="col-md">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ej: Perro" required>
            @error('nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-md">
            <label for="edad" class="form-label">Esperanza de vida</label>
            <input type="text" name="edad" class="form-control" id="edad" placeholder="Ej: 10 años">
          </div>
          <div class="col-md">
            <label for="estatus" class="form-label">Estatus</label>
            <select class="form-select form-control" name="estatus" id="estatus" required>
              <option selected disabled value="">Elegir</option>
              <option value="Viva">Viva</option>
              <option value="Extinta">Extinta</option>
            </select>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md">
            <label for="peso" class="form-label">Peso</label>
            <input type="text" name="peso" class="form-control" id="peso" placeholder="Ej: 5kg">
          </div>
          <div class="col-md">
            <label for="altura" class="form-label">Altura</label>
            <input type="text" name="altura" class="form-control" id="altura" placeholder="Ej: 2m">
          </div>
          <div class="col-md">
            <label for="longitud" class="form-label">Longitud</label>
            <input type="text" name="longitud" class="form-control" id="longitud" placeholder="Ej: 3m">
          </div>
        </div>
      </div>
    </div>

    <label for="anatomia" class="form-label">Anatomía</label>
    <textarea name="anatomia" class="form-control summernote" id="anatomia" rows="2" aria-label="With textarea"></textarea>
    
    <label for="alimentacion" class="form-label">Alimentación</label>
    <textarea name="alimentacion" class="form-control summernote" id="alimentacion" rows="2" aria-label="With textarea"></textarea>

    <label for="reproduccion" class="form-label">Reproducción y crecimiento</label>
    <textarea name="reproduccion" class="form-control summernote" id="reproduccion" rows="2" aria-label="With textarea"></textarea>
    
    <label for="distribucion" class="form-label">Distribución y hábitats</label>
    <textarea name="distribucion" class="form-control summernote" id="distribucion" rows="2" aria-label="With textarea"></textarea>
    
    <label for="habilidades" class="form-label">Habilidades</label>
    <textarea name="habilidades" class="form-control summernote" id="habilidades" rows="2" aria-label="With textarea"></textarea>
    
    <label for="domesticacion" class="form-label">Domesticación</label>
    <textarea name="domesticacion" class="form-control summernote" id="domesticacion" rows="2" aria-label="With textarea"></textarea>
    
    <label for="explotacion" class="form-label">Explotación</label>
    <textarea name="explotacion" class="form-control summernote" id="explotacion" rows="2" aria-label="With textarea"></textarea>
    
    <label for="otros" class="form-label">Otros</label>
    <textarea name="otros" class="form-control summernote" id="otros" rows="2" aria-label="With textarea"></textarea>
  </form>

</section>
<!-- /.content -->
@endsection

@section('specific-scripts')
<script>
  $(function() {
    // Summernote
    $('.summernote').summernote({
      height: 150
    })
  });
</script>
@endsection