@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Editar {{$conflicto->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('conflictos.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Editar {{$conflicto->nombre}}</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-edit-conflicto" class="position-relative needs-validation" action="{{route('conflicto.update', $conflicto->id )}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
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
            <input type="text" name="nombre" value="{{$conflicto->nombre}}" class="form-control" id="nombre" placeholder="Nombre" required>
            @error('nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col">
            <label for="ainicio" class="form-label">Fecha de inicio</label>
            <div class="input-group">
              <input id="id_inicio" type="hidden" name="id_inicio" value="0">
              <input type="number" id="dinicio" name="dinicio" class="form-select form-control" placeholder="Día">
              @error('dinicio')
              <small style="color: red">{{$message}}</small>
              @enderror
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
              @error('minicio')
              <small style="color: red">{{$message}}</small>
              @enderror
              <input type="number" id="ainicio" name="ainicio" class="form-control" placeholder="Año">
              @error('ainicio')
              <small style="color: red">{{$message}}</small>
              @enderror
            </div>
          </div>
          <div class="col">
            <label for="afin" class="form-label">Fecha de finalización</label>
            <div class="input-group">
              <input id="id_fin" type="hidden" name="id_fin" value="0">
              <input type="number" id="dfin" name="dfin" class="form-control" placeholder="Día">
              @error('dfin')
              <small style="color: red">{{$message}}</small>
              @enderror
              <select id="mfin" name="mfin" class="form-control">
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
              @error('mfin')
              <small style="color: red">{{$message}}</small>
              @enderror
              <input type="number" id="afin" name="afin" class="form-control" placeholder="Año">
              @error('afin')
              <small style="color: red">{{$message}}</small>
              @enderror
            </div>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-4">
            <label for="select_tipo" class="form-label">Tipo de conflicto</label>
            <select class="form-select form-control" name="select_tipo" id="select_tipo" required>
            @error('select_tipo')
            <small style="color: red">{{$message}}</small>
            @enderror
              <option selected disabled value="">Elegir</option>
              @foreach($tipo_conflicto as $tipo)
              <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md">
            <label for="select_padre" class="form-label">Conflicto padre</label>
            <select class="form-select form-control" name="select_padre" id="select_padre">
              <option selected disabled value="">Elegir</option>
              @foreach($conflictos as $conf)
              <option value="{{$conf->id}}">{{$conf->nombre}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label for="tipo_localizacion" class="form-label">Tipo de localización</label>
            <select class="form-select form-control" name="tipo_localizacion" id="tipo_localizacion" required>
            @error('tipo_localizacion')
            <small style="color: red">{{$message}}</small>
            @enderror
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
          <div class="col-md-3">
            <label for="atacantesp" class="form-label">Personajes atacantes</label>
            <select class="form-select form-control" multiple="multiple" data-placeholder="Atacantesp" name="atacantesp[]" id="atacantesp" style="width: 100%;">
              <option selected disabled value="">Elegir</option>
              @foreach($personajes as $persona)
              <option value="{{$persona->id}}" @foreach($atacantesp as $atacante) @if($persona->id==$atacante->id_personaje)selected @endif @endforeach >{{$persona->Nombre}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label for="defensoresp" class="form-label">Personajes defensores</label>
            <select class="form-select form-control" multiple="multiple" data-placeholder="Defensoresp" name="defensoresp[]" id="defensoresp" style="width: 100%;">
              <option selected disabled value="">Elegir</option>
              @foreach($personajes as $persona)
              <option value="{{$persona->id}}" @foreach($defensoresp as $defensor) @if($persona->id==$defensor->id_personaje)selected @endif @endforeach >{{$persona->Nombre}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label for="atacantes" class="form-label">Atacantes</label>
            <select class="form-select form-control" multiple="multiple" data-placeholder="Atacantes" name="atacantes[]" id="atacantes" style="width: 100%;">
              <option selected disabled value="">Elegir</option>
              @foreach($paises as $pais)
              <option value="{{$pais->id_organizacion}}" @foreach($atacantes as $atacante) @if($pais->id_organizacion==$atacante->id_organizacion)selected @endif @endforeach >{{$pais->nombre}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label for="defensores" class="form-label">Defensores</label>
            <select class="form-select form-control" multiple="multiple" data-placeholder="Defensores" name="defensores[]" id="defensores" style="width: 100%;">
              <option selected disabled value="">Elegir</option>
              @foreach($paises as $pais)
              <option value="{{$pais->id_organizacion}}" @foreach($defensores as $defensor) @if($pais->id_organizacion==$defensor->id_organizacion)selected @endif @endforeach >{{$pais->nombre}}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-2 mb-3">
      <div class="col">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control summernote" id="descripcion" rows="2" aria-label="With textarea">{!!$conflicto->descripcion!!}</textarea>
      </div>
    </div>
    <!----------------------------------------------->
    <label for="preludio" class="form-label">Preludio</label>
    <textarea name="preludio" class="form-control summernote" id="preludio" rows="4" aria-label="With textarea">{!!$conflicto->preludio!!}</textarea>

    <label for="desarrollo" class="form-label">Desarrollo</label>
    <textarea name="desarrollo" class="form-control summernote" id="desarrollo" rows="4" aria-label="With textarea">{!!$conflicto->desarrollo!!}</textarea>

    <label for="resultado" class="form-label">Resultado</label>
    <textarea name="resultado" class="form-control summernote" id="resultado" rows="4" aria-label="With textarea">{!!$conflicto->resultado!!}</textarea>

    <label for="consecuencias" class="form-label">Consecuencias</label>
    <textarea name="consecuencias" class="form-control summernote" id="consecuencias" rows="4" aria-label="With textarea">{!!$conflicto->consecuencias!!}</textarea>

    <label for="otros" class="form-label">Otros</label>
    <textarea name="otros" class="form-control summernote" id="otros" rows="4" aria-label="With textarea">{!!$conflicto->otros!!}</textarea>
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

    if ('{{$conflicto->fecha_inicio}}' != 0) {
      $('#id_inicio').val('{{$conflicto->fecha_inicio}}');
      $('#dinicio').val('{{$inicio->dia}}');
      $('#minicio').val('{{$inicio->mes}}');
      $('#ainicio').val('{{$inicio->anno}}');
    }

    if ('{{$conflicto->fecha_fin}}' != 0) {
      $('#id_fin').val('{{$conflicto->fecha_fin}}');
      $('#dfin').val('{{$fin->dia}}');
      $('#mfin').val('{{$fin->mes}}');
      $('#afin').val('{{$fin->anno}}');
    }
    $('#select_tipo').val('{{$conflicto->id_tipo_conflicto}}');
    $('#tipo_localizacion').val('{{$conflicto->tipo_localizacion}}');
    $('#select_padre').val('{{$conflicto->id_conflicto_padre}}');

  });
</script>
@endsection