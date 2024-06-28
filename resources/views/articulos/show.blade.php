@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

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
          
        </div>
      </div>
    </div>
    <div class="row justify-content-md-center">
      <div class="col-2">
        <a type="button" class="btn btn-success mr-1" href="{{url('/articulos/index')}}">Volver</a>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection

@section('specific-scripts')
<!-- articulos javascript -->
<script src="{{asset('dist/js/articulos.js')}}"></script>
<script>
document.getElementById("_body_").onload = function() {ver_articulo('{{$id}}')};
</script>
@endsection