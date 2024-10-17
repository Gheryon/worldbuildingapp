@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">{{$vista->nombre}}</title>
@endsection

@section('navbar-buttons')
<a href="{{route('religiones.index')}}" class="btn btn-dark">Volver</a>
<a href="{{route('religion.edit', ['id'=> $vista->id] )}}" class="btn btn-dark ml-2">Editar</a>
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
          <h3 class="mt-3">Descripción breve</h3>
          <p class="ml-2 mr-2">{!!$vista->descripcion!!}</p>
        @endif

        @if (isset($vista->historia))
          <h3>Historia</h3>
          <p class="ml-2 mr-2">{!!$vista->historia!!}</p>
        @endif

        @if (isset($vista->cosmologia))
          <h3>Cosmología</h3>
          <p class="ml-2 mr-2">{!!$vista->cosmologia!!}</p>
        @endif
        @if (isset($vista->doctrina))
          <h3>Doctrina</h3>
          <p class="ml-2 mr-2">{!!$vista->doctrina!!}</p>
        @endif
        @if (isset($vista->sagrado))
          <h3>Lugares y objetos sagrados</h3>
          <p class="ml-2 mr-2">{!!$vista->sagrado!!}</p>
        @endif
        @if (isset($vista->fiestas))
          <h3>Fiestas y rituales importantes</h3>
          <p class="ml-2 mr-2">{!!$vista->fiestas!!}</p>
        @endif
        @if (isset($vista->politica))
          <h3>Influencia política</h3>
          <p class="ml-2 mr-2">{!!$vista->politica!!}</p>
        @endif
        @if (isset($vista->estructura))
          <h3>Estructura</h3>
          <p class="ml-2 mr-2">{!!$vista->estructura!!}</p>
        @endif
        @if (isset($vista->sectas))
          <h3>Sectas</h3>
          <p class="ml-2 mr-2">{!!$vista->sectas!!}</p>
        @endif

        @if (isset($vista->otros))
        <h3>Otros</h3>
        <p class="ml-2 mr-2">{!!$vista->otros!!}</p>
        @endif
        
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body contentApp" id="content-right">

            @if ($vista->fundacion!=0)
            <h3 class="mt-2">Fecha de fundación</h3>
            <p class="ml-2 mr-2">{{$fundacion}}</p>
            @endif

            @if ($vista->disolucion!=0)
            <h3 class="mt-2">Fecha de disolución</h3>
            <p class="ml-2 mr-2">{{$disolucion}}</p>
            @endif

            @if (isset($vista->lema))
            <h3 class="mt-2">Lema</h3>
            <p class="ml-2 mr-2">{{$vista->lema}}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection