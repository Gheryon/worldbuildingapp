@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Nuevo asentamiento</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('asentamientos.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Nuevo asentamiento</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create-asentamiento" class="position-relative needs-validation" action="{{route('asentamiento.store')}}" method="post" enctype="multipart/form-data">
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
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ej: Córdoba" required>
            @error('nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-md">
            <label for="gentilicio" class="form-label">Gentilicio</label>
            <input type="text" name="gentilicio" class="form-control" id="gentilicio" placeholder="Ej: Cordobés">
          </div>
          <div class="col-md-3">
            <label for="select_tipo" class="form-label">Tipo</label>
            <select class="form-select form-control" name="select_tipo" id="select_tipo" required>
              <option selected disabled value="">Elegir</option>
                @foreach($tipo_asentamiento as $tipo)
                <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                @endforeach
            </select>
            @error('select_tipo')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-md">
            <label for="select_owner" class="form-label">Controlado por:</label>
            <select class="form-select form-control" name="select_owner" id="select_owner">
              <option selected disabled value="">Elegir</option>
                @foreach($paises as $pais)
                <option value="{{$pais->id_organizacion}}">{{$pais->nombre}}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-2">
            <label for="poblacion" class="form-label">Población</label>
            <input type="number" name="poblacion" class="form-control" id="poblacion" placeholder="Ej: 5000">
            @error('poblacion')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col">
            <label for="afundacion" class="form-label">Fundación</label>
            <div class="input-group">
              <input id="id_fundacion" type="hidden" name="id_fundacion" value="0">
              <input type="text" id="dfundacion" name="dfundacion" class="form-control" placeholder="Día">
              <select class="form-control" id="mfundacion" name="mfundacion">
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
              <input type="text" id="afundacion" name="afundacion" class="form-control" placeholder="Año">
            </div>
          </div>
          <div class="col">
            <label for="adisolucion" class="form-label">Disolución</label>
            <div class="input-group">
              <input id="id_disolucion" type="hidden" name="id_disolucion" value="0">
              <input type="text" id="ddisolucion" name="ddisolucion" class="form-control" placeholder="Día">
              <select class="form-control" id="mdisolucion" name="mdisolucion">
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
              <input type="text" id="adisolucion" name="adisolucion" class="form-control" placeholder="Año">
            </div>
          </div>
        </div>
      </div>
    </div>

    <label for="descripcion" class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control summernote" id="descripcion" rows="2" aria-label="With textarea"></textarea>
    
    <label for="demografia" class="form-label">Demografía</label>
    <textarea name="demografia" class="form-control summernote" id="demografia" rows="2" aria-label="With textarea"></textarea>

    <label for="gobierno" class="form-label">Gobierno</label>
    <textarea name="gobierno" class="form-control summernote" id="gobierno" rows="2" aria-label="With textarea"></textarea>
    
    <label for="infraestructura" class="form-label">Infraestructura</label>
    <textarea name="infraestructura" class="form-control summernote" id="infraestructura" rows="2" aria-label="With textarea"></textarea>
    
    <label for="historia" class="form-label">Historia</label>
    <textarea name="historia" class="form-control summernote" id="historia" rows="2" aria-label="With textarea"></textarea>
    
    <label for="defensas" class="form-label">Defensas</label>
    <textarea name="defensas" class="form-control summernote" id="defensas" rows="2" aria-label="With textarea"></textarea>

    <label for="cultura" class="form-label">Cultura y arquitectura</label>
    <textarea name="cultura" class="form-control summernote" id="cultura" rows="2" aria-label="With textarea"></textarea>
    
    <label for="economia" class="form-label">Economía, industria y comercio</label>
    <textarea name="economia" class="form-control summernote" id="economia" rows="2" aria-label="With textarea"></textarea>
    
    <label for="recursos" class="form-label">Recursos naturales</label>
    <textarea name="recursos" class="form-control summernote" id="recursos" rows="2" aria-label="With textarea"></textarea>
    
    <label for="geografia" class="form-label">Geografía</label>
    <textarea name="geografia" class="form-control summernote" id="geografia" rows="2" aria-label="With textarea"></textarea>
    
    <label for="clima" class="form-label">Clima</label>
    <textarea name="clima" class="form-control summernote" id="clima" rows="2" aria-label="With textarea"></textarea>

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