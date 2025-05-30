@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Editar {{$lugar->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('lugares.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Editar {{$lugar->nombre}}</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-edit-lugar" class="position-relative needs-validation" action="{{route('lugar.update', $lugar->id )}}" method="post" enctype="multipart/form-data">
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
            <input type="text" name="nombre" class="form-control" id="nombre" value="{{$lugar->nombre}}" placeholder="Nombre" required>
            @error('nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-md">
            <label for="otros_nombres" class="form-label">Otros nombres</label>
            <input type="text" name="otros_nombres" class="form-control" id="otros_nombres" value="{{$lugar->otros_nombres}}" placeholder="Otros nombres">
          </div>
          <div class="col-md-3">
            <label for="select_tipo" class="form-label">Tipo de lugar</label>
            <select class="form-control" name="select_tipo" id="select_tipo" required>
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
      </div>
    </div>
    <div class="row mt-2 mb-3">
      <div class="col">
      <label for="descripcion_breve" class="form-label">Descripción breve</label>
      <textarea name="descripcion_breve" class="form-control summernote" id="descripcion_breve" rows="2" aria-label="With textarea">{!!$lugar->descripcion_breve!!}</textarea>
      </div>
    </div>
    <!----------------------------------------------->
    <label for="geografia" class="form-label">Geografía</label>
    <textarea name="geografia" class="form-control summernote" id="geografia" rows="4" aria-label="With textarea">{!!$lugar->geografia!!}</textarea>

    <label for="ecosistema" class="form-label">Ecosistema</label>
    <textarea name="ecosistema" class="form-control summernote" id="ecosistema" rows="4" aria-label="With textarea">{!!$lugar->ecosistema!!}</textarea>
    
    <label for="clima" class="form-label">Clima</label>
    <textarea name="clima" class="form-control summernote" id="clima" rows="4" aria-label="With textarea">{!!$lugar->clima!!}</textarea>
    
    <label for="flora_fauna" class="form-label">Flora y fauna</label>
    <textarea name="flora_fauna" class="form-control summernote" id="flora_fauna" rows="4" aria-label="With textarea">{!!$lugar->flora_fauna!!}</textarea>
    
    <label for="recursos" class="form-label">Recursos</label>
    <textarea name="recursos" class="form-control summernote" id="recursos" rows="4" aria-label="With textarea">{!!$lugar->recursos!!}</textarea>
    
    <label for="historia" class="form-label">Historia</label>
    <textarea name="historia" class="form-control summernote" id="historia" rows="8" aria-label="With textarea">{!!$lugar->historia!!}</textarea>
    
    <label for="otros" class="form-label">Otros</label>
    <textarea name="otros" class="form-control summernote" id="otros" rows="4" aria-label="With textarea">{!!$lugar->otros!!}</textarea>
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

  $('#select_tipo').val('{{$lugar->id_tipo_lugar}}');
</script>
@endsection