@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">{{$vista->nombre}}</title>
@endsection

@section('navbar-buttons')
<a href="{{route('asentamientos.index')}}" class="btn btn-dark">Volver</a>
<a href="{{route('asentamiento.edit', ['id'=> $vista->id] )}}" class="btn btn-dark ml-2">Editar</a>
@endsection

@section('content')

<section class="content">
  <div class="container margin-top-20 mt-5 page">
    <div class="row article-content">
      <div class="col-md contentApp" id="content-left">
        <div class="col text-center">
          <h1>{{$vista->nombre}} </h1>
        </div>
        
        @if (isset($vista->descripcion))
          <h3 class="mt-3">Descripción</h3>
          <p class="ml-2 mr-2">{!!$vista->descripcion!!}</p>
        @endif

        @if (isset($vista->demografia))
          <h3>Demografía</h3>
          <p class="ml-2 mr-2">{!!$vista->demografia!!}</p>
        @endif

        @if (isset($vista->gobierno))
          <h3>Gobierno</h3>
          <p class="ml-2 mr-2">{!!$vista->gobierno!!}</p>
        @endif
        @if (isset($vista->infraestructura))
          <h3>Infraestructura</h3>
          <p class="ml-2 mr-2">{!!$vista->infraestructura!!}</p>
        @endif
        @if (isset($vista->historia))
          <h3>Historia</h3>
          <p class="ml-2 mr-2">{!!$vista->historia!!}</p>
        @endif
        @if (isset($vista->defensas))
          <h3>Defensas</h3>
          <p class="ml-2 mr-2">{!!$vista->defensas!!}</p>
        @endif
        @if (isset($vista->economia))
          <h3>Economía, industria y comercio</h3>
          <p class="ml-2 mr-2">{!!$vista->economia!!}</p>
        @endif
        @if (isset($vista->cultura))
          <h3>Cultura y arquitectura</h3>
          <p class="ml-2 mr-2">{!!$vista->cultura!!}</p>
        @endif
        @if (isset($vista->geografia))
          <h3>Geografía</h3>
          <p class="ml-2 mr-2">{!!$vista->geografia!!}</p>
        @endif
        @if (isset($vista->clima))
          <h3>Clima</h3>
          <p class="ml-2 mr-2">{!!$vista->clima!!}</p>
        @endif
        @if (isset($vista->recursos))
          <h3>Recursos naturales</h3>
          <p class="ml-2 mr-2">{!!$vista->recursos!!}</p>
        @endif

        @if (isset($vista->otros))
        <h3>Otros</h3>
        <p class="ml-2 mr-2">{!!$vista->otros!!}</p>
        @endif
        
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body contentApp" id="content-right">
            <h3 class="mt-2">Tipo</h3>
            <p class="ml-1 mr-2">{{$tipo}}</p>

            @if (isset($vista->gentilicio))
            <h3 class="mt-2">Gentilicio</h3>
            <p class="ml-2 mr-2">{{$vista->gentilicio}}</p>
            @endif

            @if (isset($vista->poblacion))
            <h3 class="mt-2">Población</h3>
            <p class="ml-2 mr-2">{{$vista->poblacion}}</p>
            @endif

            @if ($vista->fundacion!=0)
            <h3 class="mt-2">Fecha de fundación</h3>
            <p class="ml-2 mr-2">{{$fundacion}}</p>
            @endif

            @if ($vista->disolucion!=0)
            <h3 class="mt-2">Fecha de disolución</h3>
            <p class="ml-2 mr-2">{{$disolucion}}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection