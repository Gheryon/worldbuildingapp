@extends('layouts.index')

@section('title')
<title id="title">{{$organizacion->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('organizaciones.index')}}" class="btn btn-dark">Volver</a>
  <a href="{{route('organizacion.edit', ['id'=> $organizacion->id] )}}" class="btn btn-dark ml-2">Editar</a>
</li>
@endsection

@section('content')
<section class="content mt-4">
  <div class="container-fluid">
    <div class="row">
      {{-- Columna principal: información --}}
      <div class="col-md-8">
        <div class="card card-outline card-dark">
          <div class="card-header">
            <h1 class="card-title" style="font-size: 2rem;">
              {{ $organizacion->nombre }}
            </h1>
          </div>
          <div class="card-body">
            @if ($organizacion->descripcion_breve)
            <h2 class="border-bottom pb-2 mb-3">Descripción breve</h2>
            <div class="ml-4 mb-3">{!! $organizacion->descripcion_breve !!}</div>
            @endif

            @if ($organizacion->historia)
            <h2 class="border-bottom pb-2 mb-3">Historia</h2>
            <div class="ml-4 mb-3">{!! $organizacion->historia !!}</div>
            @endif

            {{-- Sección de estructura y política --}}
            @php
            $hasInternal = $organizacion->estructura || $organizacion->geopolitica || $organizacion->militar;
            @endphp

            @if ($hasInternal)
            <h2 class="border-bottom pb-2 mb-3">Estructura, política y militar</h2>


            @if ($organizacion->estructura)
            <h5><i class="fas fa-sitemap mr-2"></i>Estructura organizativa</h5>
            <div class="ml-4 mb-3">{!! $organizacion->estructura !!}</div>
            @endif

            @if ($organizacion->geopolitica)
            <h5><i class="fas fa-globe-americas mr-2"></i>Geopolítica</h5>
            <div class="ml-4 mb-3">{!! $organizacion->geopolitica !!}</div>
            @endif

            @if ($organizacion->militar)
            <h5><i class="fas fa-shield-alt mr-2"></i>Poder militar</h5>
            <div class="ml-4 mb-3">{!! $organizacion->militar !!}</div>
            @endif
            @endif

            {{-- Sección de sociedad y cultura --}}
            @php
            $hasSocial = $organizacion->demografia || $organizacion->cultura || $organizacion->religion || $organizacion->educacion;
            @endphp

            @if ($hasSocial)
            <h2 class="border-bottom pb-2 mt-4 mb-3">Sociedad y cultura</h2>

            @if ($organizacion->demografia)
            <h5><i class="fas fa-users mr-2"></i>Demografía</h5>
            <div class="ml-4 mb-3">{!! $organizacion->demografia !!}</div>
            @endif

            @if ($organizacion->cultura)
            <h5><i class="fas fa-theater-masks mr-2"></i>Cultura y tradiciones</h5>
            <div class="ml-4 mb-3">{!! $organizacion->cultura !!}</div>
            @endif

            @if ($organizacion->religion)
            <h5><i class="fas fa-monument mr-2"></i>Presencia religiosa</h5>
            <div class="ml-4 mb-3">{!! $organizacion->religion !!}</div>
            @endif

            @if ($organizacion->educacion)
            <h5><i class="fas fa-graduation-cap mr-2"></i>Educación</h5>
            <div class="ml-4 mb-3">{!! $organizacion->educacion !!}</div>
            @endif
            @endif

            {{-- Sección de Economía y Recursos --}}
            @php
            $hasEco = $organizacion->tecnologia || $organizacion->economia || $organizacion->recursos_naturales || $organizacion->territorio;
            @endphp

            @if ($hasEco)
            <h2 class="border-bottom pb-2 mt-4 mb-3">Recursos y Territorio</h2>

            @if ($organizacion->territorio)
            <h5><i class="fas fa-map mr-2"></i>Extensión y territorio</h5>
            <div class="ml-4 mb-3">{!! $organizacion->territorio !!}</div>
            @endif

            @if ($organizacion->economia)
            <h5><i class="fas fa-coins mr-2"></i>Economía y comercio</h5>
            <div class="ml-4 mb-3">{!! $organizacion->economia !!}</div>
            @endif

            @if ($organizacion->recursos_naturales)
            <h5><i class="fas fa-leaf mr-2"></i>Recursos naturales</h5>
            <div class="ml-4 mb-3">{!! $organizacion->recursos_naturales !!}</div>
            @endif

            @if ($organizacion->tecnologia)
            <h5><i class="fas fa-microchip mr-2"></i>Tecnología y ciencia</h5>
            <div class="ml-4 mb-3">{!! $organizacion->tecnologia !!}</div>
            @endif
            @endif

            @if ($organizacion->otros)
            <h2 class="border-bottom pb-2 mt-4 mb-3">Otros detalles</h2>
            <div class="ml-4">{!! $organizacion->otros !!}</div>
            @endif
          </div>{{-- fin card-body --}}
        </div>
      </div>

      {{-- Columna lateral: ficha técnica --}}
      <div class="col-md-4">
        <div class="card card-dark">
          <div class="card-header">
            <h3 class="card-title">Ficha de organización</h3>
          </div>
          <div class="card-body" id="content-right">
            <h3>Escudo</h3>
            <div class="row">
              <img alt="escudo" id="escudo" class="img-thumbnail shadow-sm mb-3" src="{{asset("storage/escudos/{$organizacion->escudo}")}}" width="300" height="300">
            </div>
            <strong class="mt-2">Tipo</strong>
            <p class="text-muted">{{$organizacion->tipo->nombre}}</p>

            @if ($organizacion->gentilicio)
            <strong class="mt-2">Gentilicio</strong>
            <p class="text-muted">{{$organizacion->gentilicio}}</p>
            @endif

            @if($organizacion->ruler->id!=0)
            <strong>Líder:</strong>
            <p class="text-muted"><a href="{{route('personaje.show', [$organizacion->ruler->id] )}}">{{$organizacion->ruler->nombre}}</a></p>
            @endif

            @if($organizacion->owner->id!=0)
            <strong>Controlado por:</strong>
            <p class="text-muted">
              <a href="{{ route('organizacion.show', $organizacion->owner->id) }}">{{ $organizacion->owner->nombre }}</a>
            </p>
            @endif

            <strong>Fundación:</strong>
            <p class="text-muted">{{ $fundacion }}</p>
            @if ($organizacion->disolucion != 0)
            <strong>Disolución:</strong>
            <p class="text-muted">{{ $disolucion}} {{$organizacion->disolucion}}</p>
            @endif

            @if($organizacion->religiones->isNotEmpty())
            <strong>Religiones:</strong>
            @foreach($organizacion->religiones as $religion)
            <p class="ml-1 mr-2 mb-0"><a href="{{route('religion.show', [$religion->id] )}}">{{$religion->nombre}}</a></p>
            @endforeach
            @endif

            @if($organizacion->subordinates->isNotEmpty())
            <strong >Organizaciones subordinadas:</strong>
            @foreach($organizacion->subordinates as $subordinate)
            <p class="ml-1 mr-2 mb-0"><a href="{{route('organizacion.show', [$subordinate->id] )}}">{{$subordinate->nombre}}</a></p>
            @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection