@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">{{$vista->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('organizaciones.index')}}" class="btn btn-dark">Volver</a>
<a href="{{route('organizacion.edit', ['id'=> $vista->id_organizacion] )}}" class="btn btn-dark ml-2">Editar</a>
</li>
@endsection

@section('content')
<section class="content">
  <div class="container margin-top-20 mt-5 page">
    <div class="row article-content">
      <div class="col-md contentApp" id="content-left">
        <div class="col text-center">
          <h1>{{$vista->nombre}} </h1>
        </div>
        
        @if (isset($vista->descripcionBreve))
          <h3 class="mt-3">Descripción breve</h3>
          <p class="ml-2 mr-2 mt-1">{!!$vista->descripcionBreve!!}</p>
        @endif

        @if (isset($vista->historia))
          <h2 class="mt-2">Historia</h2>
          <p class="ml-2 mr-2 mt-1">{!!$vista->historia!!}</p>
        @endif

        @if (isset($vista->estructura))
          <h3 class="mt-2">Estructura política</h3>
          <p class="ml-2 mr-2 mt-1">{!!$vista->estructura!!}</p>
        @endif
        @if (isset($vista->politicaExteriorInterior))
          <h3 class="mt-2">Política exterior e interior</h3>
          <p class="ml-2 mr-2 mt-1">{!!$vista->politicaExteriorInterior!!}</p>
        @endif
        @if (isset($vista->militar))
          <h3 class="mt-2">Militar</h3>
          <p class="ml-2 mr-2 mt-1">{!!$vista->militar!!}</p>
        @endif
        @if (isset($vista->territorio))
          <h3 class="mt-2">Territorio</h3>
          <p class="ml-2 mr-2 mt-1">{!!$vista->territorio!!}</p>
        @endif

        @if (isset($vista->demografia))
          <h3 class="mt-2">Demografía</h3>
          <p class="ml-2 mr-2 mt-1">{!!$vista->demografia!!}</p>
        @endif
        @if (isset($vista->religion))
          <h3 class="mt-2">Religión</h3>
          <p class="ml-2 mr-2 mt-1">{!!$vista->religion!!}</p>
        @endif
        @if (isset($vista->educacion))
          <h3 class="mt-2">Educación</h3>
          <p class="ml-2 mr-2 mt-1">{!!$vista->educacion!!}</p>
        @endif
        @if (isset($vista->cultura))
          <h3 class="mt-2">Elementos culturales</h3>
          <p class="ml-2 mr-2 mt-1">{!!$vista->cultura!!}</p>
        @endif
        
        @if (isset($vista->economia))
          <h3 class="mt-2">Economía</h3>
          <p class="ml-2 mr-2 mt-1">{!!$vista->economia!!}</p>
        @endif
        @if (isset($vista->recursosNaturales))
          <h3 class="mt-2">Recursos naturales</h3>
          <p class="ml-2 mr-2 mt-1"><{!!$vista->recursosNaturales!!}</p>
        @endif

        @if (isset($vista->otros))
        <h2>Otros</h2>
        <p class="ml-2 mr-2 mt-1">{!!$vista->otros!!}</p>
        @endif
        
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body contentApp" id="content-right">
            <h3>Escudo</h3>
            <div class="row">
              <img alt="escudo" id="escudo" class="img-fluid" src="{{asset("storage/escudos/{$vista->escudo}")}}" width="300" height="300">
            </div>
            <h3 class="mt-2">Tipo</h3>
            <p class="ml-1 mr-2">{{$tipo}}</p>

            @if (isset($vista->gentilicio))
            <h3 class="mt-2">Gentilicio</h3>
            <p class="ml-2 mr-2">{{$vista->gentilicio}}</p>
            @endif

            @if (isset($soberano->id))
            <h3 class="mt-2">Actual soberano</h3>
            <p class="ml-2 mr-2"><a href="{{route('personaje.show',$soberano->id)}}">{{$soberano->Nombre}}</a></p>
            @endif

            @if (isset($vista->capital))
            <h3 class="mt-2">Capitial</h3>
            <p class="ml-2 mr-2">{{$vista->capital}}</p>
            @endif

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

            @if (isset($owner->id_organizacion))
              <h3 class="mt-2">Bajo control de:</h3>
              <p class="ml-1 mr-2"><a href="{{route('organizacion.show',$owner->id_organizacion)}}">{{$owner->nombre}}</a></p>
            @endif
            
            @if (isset($religiones))
            @if (filled($religiones))
            <h3 class="mt-2">Religiones presentes</h3>
            @foreach($religiones as $rel)
            <p class="ml-1 mr-2"><a href="{{route('religion.show', [$rel->id] )}}">{{$rel->nombre}}</a></p>
            @endforeach
            @endif
            @endif

            @if (isset($subditos))
              @if (filled($subditos))
              <h3 class="mt-2">Súbditos</h3>
              @foreach($subditos as $subdito)
                <p class="ml-1 mr-2"><a href="{{route('organizacion.show', [$subdito->id_organizacion] )}}">{{$subdito->nombre}}</a></p>
              @endforeach
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection