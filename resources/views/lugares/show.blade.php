@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">{{$vista->nombre}}</title>
@endsection

@section('navbar-buttons')
<a href="{{route('lugares.index')}}" class="btn btn-dark">Volver</a>
<a href="{{route('lugar.edit', ['id'=> $vista->id] )}}" class="btn btn-dark ml-2">Editar</a>
@endsection

@section('content')

<section class="content">
  <div class="container margin-top-20 mt-5 page">
    <div class="row article-content">
      <div class="col-md contentApp" id="content-left">
        <div class="col text-center">
          <h1>{{$vista->nombre}} </h1>
        </div>
        
        @if (isset($vista->descripcion_breve))
          <h3 class="mt-3">Descripción breve</h3>
          <p class="ml-2 mr-2">{!!$vista->descripcion_breve!!}</p>
        @endif

        @if (isset($vista->historia))
          <h3>Historia</h3>
          <p class="ml-2 mr-2">{!!$vista->historia!!}</p>
        @endif

        @if (isset($vista->geografia))
          <h3>Geografía</h3>
          <p class="ml-2 mr-2">{!!$vista->geografia!!}</p>
        @endif
        @if (isset($vista->ecosistema))
          <h3>Ecosistema</h3>
          <p class="ml-2 mr-2">{!!$vista->ecosistema!!}</p>
        @endif
        @if (isset($vista->clima))
          <h3>Clima</h3>
          <p class="ml-2 mr-2">{!!$vista->clima!!}</p>
        @endif
        @if (isset($vista->flora_fauna))
          <h3>Flora y fauna</h3>
          <p class="ml-2 mr-2">{!!$vista->flora_fauna!!}</p>
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

            @if (isset($owner->id_organizacion))
              <h3 class="mt-2">Bajo control de:</h3>
              <p class="ml-1 mr-2"><a href="{{route('organizacion.show',$owner->id_organizacion)}}">{{$owner->nombre}}</a></p>
            @endif

            @if (isset($vista->otros_nombres))
            <h3 class="mt-2">Otros nombres</h3>
            <p class="ml-2 mr-2">{{$vista->otros_nombres}}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection