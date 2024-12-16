@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Nuevo conflicto</title>
@endsection

@section('navbar-buttons')
<a href="{{route('conflictos.index')}}" class="btn btn-dark">Cancelar</a>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Nuevo conflicto</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create-conflicto" class="position-relative needs-validation" action="{{route('conflicto.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row justify-content-md-center">
      <div class="col-md-auto form-actions">
        <button type="submit" id="submit-crear-button" class="btn btn-success">Guardar</button>
        <a class="btn btn-primary" type="button" id="volver-crear-button" href="{{route('conflictos.index')}}" style="display:none">Volver</a>
      </div>
    </div>
    <div class="row mt-3 mb-3 justify-content-md-center border">
      <div class="col">
        <div class="row mt-2">
          <div class="col-md">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required>
            @error('nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-md-3">
            <label for="select_tipo" class="form-label">Tipo de conflicto</label>
            <select class="form-select form-control" name="select_tipo" id="select_tipo" required>
              <option selected disabled value="">Elegir</option>
                @foreach($tipo_conflicto as $tipo)
                <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                @endforeach
            </select>
          </div>
						<div class="col-md-3">
							<label for="tipo_localizacion" class="form-label">Tipo de localización</label>
							<select class="form-select form-control" name="tipo_localizacion" id="tipo_localizacion">
								<option selected disabled value="">Elegir</option>
								<option>Aéreo</option>
								<option>Marítimo</option>
								<option>Mixto</option>
								<option>Terrestre</option>
								<option>Urbano</option>
							</select>
						</div>
        </div>
        <div class="row mt-2">
          <div class="col">
            <label for="id_inicio" class="form-label">Fecha de inicio</label>
            <div class="input-group">
              <input id="id_inicio" type="hidden" name="id_inicio" value="0">
              <input type="number" id="dinicio" name="dinicio" class="form-select form-control" placeholder="Día">
              <select id="minicio" name="minicio" class="form-control">
                <option selected disabled value="">Mes</option>
                <option value="0">Semana de año nuevo</option>
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
              </select>
              <input type="number" id="ainicio" name="ainicio" class="form-control" placeholder="Año">
            </div>
          </div>
          <div class="col">
              <label for="id_fin" class="form-label">Fecha de finalización</label>
            <div class="input-group">
              <input id="id_fin" type="hidden" name="id_fin" value="0">
              <input type="number" id="dfin" name="dfin" class="form-control" placeholder="Día">
              <select id="mfin" name="mfin" class="form-select form-control" placeholder="Mes">
                <option selected disabled value="">Mes</option>
                <option value="0">Semana de año nuevo</option>
                <option value="1">Enero</option>
                <option value="2">Febrero</option>
                <option value="3">Marzo</option>
                <option value="4">Abril</option>
                <option value="5">Mayo</option>
                <option value="6">Junio</option>
                <option value="7">Julio</option>
                <option value="8">Agosto</option>
                <option value="9">Septiembre</option>
                <option value="10">Octubre</option>
                <option value="11">Noviembre</option>
                <option value="12">Diciembre</option>
              </select>
              <input type="number" id="afin" name="afin" class="form-control" placeholder="Año">
            </div>
          </div>
          
          <div class="col-md-3">
            <label for="atacantes" class="form-label">Atacantes</label>
            <select class="form-select form-control" multiple="multiple" data-placeholder="Atacantes" name="atacantes[]" id="atacantes" style="width: 100%;">
              <option selected disabled value="">Elegir</option>
              @foreach($paises as $pais)
              <option value="{{$pais->id_organizacion}}">{{$pais->nombre}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label for="defensores" class="form-label">Defensores</label>
            <select class="form-select form-control" multiple="multiple" data-placeholder="Defensores" name="defensores[]" id="defensores" style="width: 100%;">
              <option selected disabled value="">Elegir</option>
              @foreach($paises as $pais)
              <option value="{{$pais->id_organizacion}}">{{$pais->nombre}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-2 mb-3">
      <div class="col">
      <label for="descripcion" class="form-label">Descripción</label>
      <textarea name="descripcion" class="form-control summernote" id="descripcion" rows="2" aria-label="With textarea"></textarea>
      </div>
    </div>
    <!----------------------------------------------->
    <label for="preludio" class="form-label">Preludio</label>
    <textarea name="preludio" class="form-control summernote" id="preludio" rows="4" aria-label="With textarea"></textarea>

    <label for="desarrollo" class="form-label">Desarrollo</label>
    <textarea name="desarrollo" class="form-control summernote" id="desarrollo" rows="4" aria-label="With textarea"></textarea>
    
    <label for="resultado" class="form-label">resultado</label>
    <textarea name="resultado" class="form-control summernote" id="resultado" rows="4" aria-label="With textarea"></textarea>
    
    <label for="consecuencias" class="form-label">Consecuencias</label>
    <textarea name="consecuencias" class="form-control summernote" id="consecuencias" rows="4" aria-label="With textarea"></textarea>

    <label for="otros" class="form-label">Otros</label>
    <textarea name="otros" class="form-control summernote" id="otros" rows="4" aria-label="With textarea"></textarea>
  </form>

</section>
<!-- /.content -->
@endsection

@section('specific-scripts')
<script>
  $(function() {
    // Summernote
    $('.summernote').summernote({
      height: 150,
    })
  });
</script>
@endsection