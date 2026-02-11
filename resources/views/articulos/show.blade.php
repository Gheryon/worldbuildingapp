@extends('layouts.index')

@section('title')
<title id="title">{{$articulo->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a role="button" title="Volver" href="{{route('articulos.index')}}" class="nuevo btn btn-dark"><i class="fas fa-arrow-left mr-1"></i>Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('articulos.edit',$articulo->id)}}" role="button" title="Editar" class="btn ml-1 btn-dark"><i class="fas fa-pencil-alt mr-2"></i>Editar</a>
</li>
@endsection

@section('content')
<article class="content">
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-md-10">

        <header class="mb-5 border-bottom pb-3">
          <h1 class="display-4 fw-bold text-dark mb-2">{{ $articulo->nombre }}</h1>
          <p class="text-muted small">
            <i class="fas fa-calendar-alt mr-1"></i> Última actualización: {{ $articulo->updated_at->format('d/m/Y') }}
          </p>
        </header>

        <div class="article-body" id="contenido" style="line-height: 1.8; font-size: 1.1rem;">
          {!! clean($articulo->contenido) !!}
        </div>

        <footer class="mt-5 pt-4 border-top">
          <div class="d-flex justify-content-between">
            <span class="text-muted small">ID de artículo: #{{ $articulo->id }}</span>
          </div>
        </footer>

      </div>
    </div>
  </div>
</article>
@endsection