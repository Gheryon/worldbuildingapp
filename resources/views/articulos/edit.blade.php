@extends('layouts.index')

@section('title')
<title id="title">Editar {{$articulo->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('articulos')}}" class="btn btn-dark">Cancelar</a>
</li>
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
      <button type="submit" class="btn btn-success ml-1" id="guardar-button">Guardar</button>
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
                  <select class="form-control" name="tipo" id="tipo" required>
                    <option selected disabled value="">Elegir</option>
                    <option>Referencia</option>
                    <option>Canon</option>
                  </select>
                  @error('tipo')
                  <small style="color: red">{{$message}}</small>
                  @enderror
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
    $('#tipo').val('{{$articulo->tipo}}');
  });
</script>
@endsection