@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">{{$articulo->nombre}}</title>
@endsection

@section('navbar-buttons')
<a type="button" title="Volver" href="{{route('articulos')}}" class="nuevo btn btn-dark">Volver</a>
<a href="{{route('articulos.edit',$articulo->id_articulo)}}" type="button" title="Editar" class="btn ml-1 btn-dark"><i class="fas fa-pencil-alt mr-2"></i>Editar</a>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-1">
      <div class="col-sm-12">
        <h1 id="content-title-h1" class="fw-bolder text-center contentApp"></h1>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container margin-top-20 mt-5 page">
    <div class="row article-content">
      <div class="col">
        <div class="row personaje" id="contenido">
          {!!$articulo->contenido!!}
        </div>
      </div>
    </div>
    <div class="row justify-content-md-center">
      <div class="col-2">
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection

@section('specific-scripts')

@endsection