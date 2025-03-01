@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Nueva construcción</title>
@endsection

@section('navbar-buttons')
<a href="{{route('construcciones.index')}}" class="btn btn-dark">Cancelar</a>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Nueva construcción</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create-construccion" class="position-relative needs-validation" action="{{route('construccion.store')}}" method="post" enctype="multipart/form-data">
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
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required>
            @error('nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-md">
          <label for="select_ubicacion" class="form-label">Ubicación</label>
            <select class="form-select form-control" name="select_ubicacion" id="select_ubicacion">
              <option selected disabled value="">Elegir</option>
                @foreach($ubicaciones as $ubicacion)
                <option value="{{$ubicacion->id}}">{{$ubicacion->nombre}}</option>
                @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label for="select_tipo" class="form-label">Tipo de construccion</label>
            <select class="form-select form-control" name="select_tipo" id="select_tipo" required>
              <option selected disabled value="">Elegir</option>
                @foreach($tipos as $tipo)
                <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                @endforeach
            </select>
            @error('select_tipo')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
        </div>
        
        <div class="row">
          <div class="col">
            <label for="construccion" class="form-label">Construcción</label>
            <div class="input-group">
              <input id="id_construccion" type="hidden" name="id_construccion" value="0">
              <input type="text" id="dconstruccion" name="dconstruccion" class="form-control" placeholder="Día">
              <select class="form-select form-control" type="number" id="mconstruccion" name="mconstruccion">
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
              <input type="text" id="aconstruccion" name="aconstruccion" class="form-control" placeholder="Año">
            </div>
          </div>
          <div class="col">
            <label for="destruccion" class="form-label">Destrucción</label>
            <div class="input-group">
              <input id="id_destruccion" type="hidden" name="id_destruccion" value="0">
              <input type="text" id="ddestruccion" name="ddestruccion" class="form-control" placeholder="Día">
              <select class="form-select form-control" type="number" id="mdestruccion" name="mdestruccion">
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
              <input type="text" id="adestruccion" name="adestruccion" class="form-control" placeholder="Año">
            </div>
          </div>
        </div>
      </div>
    </div>

    <label for="descripcion" class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control summernote" id="descripcion" rows="2" aria-label="With textarea"></textarea>
    
    <label for="historia" class="form-label">Historia</label>
    <textarea name="historia" class="form-control summernote" id="historia" rows="8" aria-label="With textarea"></textarea>
    
    <label for="proposito" class="form-label">Propósito</label>
    <textarea name="proposito" class="form-control summernote" id="proposito" rows="4" aria-label="With textarea"></textarea>
    
    <label for="aspecto" class="form-label">Aspecto</label>
    <textarea name="aspecto" class="form-control summernote" id="aspecto" rows="4" aria-label="With textarea"></textarea>

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
      height: 150
    })
  });
</script>
@endsection