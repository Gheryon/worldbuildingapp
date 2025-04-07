@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Editar {{$articulo->nombre}}</title>
@endsection

@section('navbar-buttons')
<a href="{{route('articulos')}}" class="btn btn-dark">Cancelar</a>
@endsection

@section('content')
<div class="row">
  <h1 id="title-h1">Editar artículo {{$articulo->nombre}}</h1>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-edit"  action="{{route('articulos.update', $articulo->id_articulo )}}" method="post">
    @csrf
    @method('PUT')
    <div class="row mb-3 justify-content-center">
      <a type="button" class="btn btn-danger mr-1" id="cancelar-button" href="{{url('/articulos/index')}}">Cancelar</a>
      <button type="submit" class="btn btn-success ml-1" id="guardar-button">Guardar</button>
      <a type="button" class="btn btn-primary" id="volver-editar-button" href="{{url('/articulos/index')}}" style="display:none">Volver</a>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline">
            <div class="card-header">
              <div class="row mb-2">
                <div class="col">
                  <label for="nombre" class="form-label">Nombre</label>
                  <input type="text" value="{{$articulo->nombre}}" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required>
                  @error('nombre')
                  <small style="color: red">{{$message}}</small>
                  @enderror
                </div>
                <div class="col-4">
                  <label for="tipo">Tipo</label>
                  <select class="form-select form-control" name="tipo" id="tipo">
                    <option>Referencia</option>
                    <option>Canon</option>
                    <option>Crónica</option>
                  </select>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <label for="contenido" class="form-label">Contenido</label>
              <textarea class="form-control summernote" id="contenido" name="contenido" rows="8" aria-label="With textarea">{!!$articulo->contenido!!}</textarea>
                @error('contenido')
                <small style="color: red">{{$message}}</small>
                @enderror
            </div>
            <div class="card-footer">
            </div>
          </div>
        </div><!-- /.col-->
      </div><!-- ./row -->
    </div> <!-- /container -->
  </form>
</section>
<!-- /.content -->

@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
<script>
  $(function() {
    // Summernote
    $('.summernote').summernote({
      height: 300
    })

    $('#tipo').val('{{$articulo->tipo}}');
  });
</script>
@endsection