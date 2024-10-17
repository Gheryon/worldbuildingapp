@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Editar {{$religion->nombre}}</title>
@endsection

@section('navbar-buttons')
<a href="{{route('religiones.index')}}" class="btn btn-dark">Cancelar</a>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Editar {{$religion->nombre}}</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create-religion" class="position-relative needs-validation" action="{{route('religion.update', $religion->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row justify-content-md-center">
      <div class="col-md-auto form-actions">
        <button type="submit" id="submit-crear-button" class="btn btn-success">Guardar</button>
        <a class="btn btn-primary" type="button" id="volver-crear-button" href="{{route('religiones.index')}}" style="display:none">Volver</a>
      </div>
    </div>
    <div class="row mt-3 mb-3 justify-content-md-center border">
      <div class="col">
        <div class="row mt-2">
          <div class="col-md">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" id="nombre" value="{{$religion->nombre}}" placeholder="Nombre" required>
            @error('nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-md">
            <label for="lema" class="form-label">Lema</label>
            <input type="text" name="lema" class="form-control" id="lema" value="{{$religion->lema}}" placeholder="Lema">
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md">
            <label for="id_fundacion" class="form-label">Fecha de fundacion</label>
            <div class="input-group">
              <input id="id_fundacion" type="hidden" name="id_fundacion" value="0">
              <input type="number" id="dfundacion" name="dfundacion" class="form-select form-control" placeholder="Día">
              <select id="mfundacion" name="mfundacion" class="form-control">
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
              <input type="number" id="afundacion" name="afundacion" class="form-control" placeholder="Año">
            </div>
          </div>
          <div class="col-md">
              <label for="id_disolucion" class="form-label">Fecha de disolucion</label>
            <div class="input-group">
              <input id="id_disolucion" type="hidden" name="id_disolucion" value="0">
              <input type="number" id="ddisolucion" name="ddisolucion" class="form-control" placeholder="Día">
              <select id="mdisolucion" name="mdisolucion" class="form-select form-control" placeholder="Mes">
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
              <input type="number" id="adisolucion" name="adisolucion" class="form-control" placeholder="Año">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <label for="escudo" class="form-label">Escudo</label>
        <img alt="escudo" id="escudo-img" src="{{asset("storage/escudos/default.png")}}" class="img-fluid" style="width: 50%;">
        <input type="file" name="escudo" class="form-control" id="escudo" value="{{$religion->escudo}}">
      </div>
    </div>
    <div class="row mt-2 mb-3">
      <div class="col">
      <label for="descripcion" class="form-label">Descripción</label>
      <textarea name="descripcion" class="form-control summernote" id="descripcion" rows="2" aria-label="With textarea">{!!$religion->descripcion!!}</textarea>
      </div>
    </div>
    <!----------------------------------------------->
    <label for="historia" class="form-label">Historia</label>
    <textarea name="historia" class="form-control summernote" id="historia" rows="8" aria-label="With textarea">{!!$religion->historia!!}</textarea>

    <label for="cosmologia" class="form-label">Cosmología</label>
    <textarea name="cosmologia" class="form-control summernote" id="cosmologia" rows="4" aria-label="With textarea">{!!$religion->cosmologia!!}</textarea>

    <label for="doctrina" class="form-label">Doctrina</label>
    <textarea name="doctrina" class="form-control summernote" id="doctrina" rows="4" aria-label="With textarea">{!!$religion->doctrina!!}</textarea>
    
    <label for="sagrado" class="form-label">Lugares y objetos sagrados</label>
    <textarea name="sagrado" class="form-control summernote" id="sagrado" rows="4" aria-label="With textarea">{!!$religion->sagrado!!}</textarea>
    
    <label for="fiestas" class="form-label">Fiestas y rituales importantes</label>
    <textarea name="fiestas" class="form-control summernote" id="fiestas" rows="4" aria-label="With textarea">{!!$religion->fiestas!!}</textarea>
    
    <label for="politica" class="form-label">Influencia política</label>
    <textarea name="politica" class="form-control summernote" id="politica" rows="4" aria-label="With textarea">{!!$religion->politica!!}</textarea>
    
    <label for="estructura" class="form-label">Estructura</label>
    <textarea name="estructura" class="form-control summernote" id="estructura" rows="4" aria-label="With textarea">{!!$religion->estructura!!}</textarea>

    <label for="sectas" class="form-label">Sectas</label>
    <textarea name="sectas" class="form-control summernote" id="sectas" rows="4" aria-label="With textarea">{!!$religion->sectas!!}</textarea>
    
    <label for="otros" class="form-label">Otros</label>
    <textarea name="otros" class="form-control summernote" id="otros" rows="4" aria-label="With textarea">{!!$religion->otros!!}</textarea>
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

  if('{{$religion->fundacion}}'!=0){
    $('#id_fundacion').val('{{$religion->fundacion}}');
    $('#dfundacion').val('{{$fundacion->dia}}');
    $('#mfundacion').val('{{$fundacion->mes}}');
    $('#afundacion').val('{{$fundacion->anno}}');
  }

  if('{{$religion->disolucion}}'!=0){
    $('#id_disolucion').val('{{$religion->disolucion}}');
    $('#ddisolucion').val('{{$disolucion->dia}}');
    $('#mdisolucion').val('{{$disolucion->mes}}');
    $('#adisolucion').val('{{$disolucion->anno}}');
  }
</script>
@endsection