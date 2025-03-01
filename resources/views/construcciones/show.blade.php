@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">{{$vista->nombre}}</title>
@endsection

@section('navbar-buttons')
<a href="{{route('construcciones.index')}}" class="btn btn-dark">Volver</a>
<a href="{{route('construccion.edit', ['id'=> $vista->id] )}}" class="btn btn-dark ml-2">Editar</a>
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

        @if (isset($vista->historia))
        <h3>Historia</h3>
        <p class="ml-2 mr-2">{!!$vista->historia!!}</p>
        @endif

        @if (isset($vista->proposito))
        <h3>Propósito</h3>
        <p class="ml-2 mr-2">{!!$vista->proposito!!}</p>
        @endif
        @if (isset($vista->aspecto))
        <h3>Aspecto</h3>
        <p class="ml-2 mr-2">{!!$vista->aspecto!!}</p>
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

            @if (isset($ubicacion->id))
            <h3 class="mt-2">Situado en:</h3>
            <p class="ml-1 mr-2"><a href="{{route('asentamiento.show',$ubicacion->id)}}">{{$ubicacion->nombre}}</a></p>
            @endif

            @if ($vista->construccion!=0)
            <h3 class="mt-2">Fecha de construcción</h3>
            <p class="ml-2 mr-2">{{$construccion}}</p>
            @endif

            @if ($vista->destruccion!=0)
            <h3 class="mt-2">Fecha de destrucción</h3>
            <p class="ml-2 mr-2">{{$destruccion}}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection