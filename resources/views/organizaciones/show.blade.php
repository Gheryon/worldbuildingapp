@extends('layouts.index')

@section('title')
<title id="title">{{$organizacion->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('organizaciones.index')}}" class="btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('organizaciones.edit', $organizacion->id )}}" class="btn btn-dark ml-2">Editar</a>
</li>
@endsection

@section('content')
<div class="container-fluid py-5 page">
  <div class="container">
    {{-- Encabezado --}}
    <div class="row mb-5">
      <div class="col-12 text-center text-md-left border-bottom-dark pb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <div>
            <h1 class="display-4 font-weight-bold mb-1 text-primary-custom">{{ $organizacion->nombre }}</h1>
            <p class="lead text-secondary-custom font-italic">
              {{ $organizacion->tipo->nombre ?? 'Tipo desconocido' }}
              @if($organizacion->lema)
              — "{{ $organizacion->lema }}"
              @endif
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      {{-- Columna Principal: Contenido --}}
      <div class="col-lg-8">
        <div class="pr-lg-4">

          @if ($organizacion->descripcion_breve)
          <section class="mb-5">
            <h2 class="h3 font-weight-bold mb-3 text-secondary-custom">
              <i class="fas fa-feather-alt mr-2 opacity-75"></i>Resumen
            </h2>
            <div class="article-body text-justify">
              {!! clean($organizacion->descripcion_breve) !!}
            </div>
          </section>
          @endif

          @php
          $secciones = [
          ['titulo' => 'Historia', 'campo' => $organizacion->historia, 'icono' => 'fa-scroll'],
          ['titulo' => 'Estructura organizativa', 'campo' => $organizacion->estructura, 'icono' => 'fa-sitemap'],
          ['titulo' => 'Geopolítica', 'campo' => $organizacion->geopolitica, 'icono' => 'fa-globe-americas'],
          ['titulo' => 'Poder militar', 'campo' => $organizacion->militar, 'icono' => 'fa-shield-alt'],
          ['titulo' => 'Demografía', 'campo' => $organizacion->demografia, 'icono' => 'fa-users'],
          ['titulo' => 'Cultura y tradiciones', 'campo' => $organizacion->cultura, 'icono' => 'fa-theater-masks'],
          ['titulo' => 'Presencia religiosa', 'campo' => $organizacion->religion, 'icono' => 'fa-monument'],
          ['titulo' => 'Educación', 'campo' => $organizacion->educacion, 'icono' => 'fa-graduation-cap'],
          ['titulo' => 'Tecnología y ciencia', 'campo' => $organizacion->tecnologia, 'icono' => 'fa-microchip'],
          ['titulo' => 'Economía y comercio', 'campo' => $organizacion->economia, 'icono' => 'fa-coins'],
          ['titulo' => 'Extensión y territorio', 'campo' => $organizacion->territorio, 'icono' => 'fa-map'],
          ['titulo' => 'Recursos naturales', 'campo' => $organizacion->recursos_naturales, 'icono' => 'fa-leaf'],
          ['titulo' => 'Otros detalles', 'campo' => $organizacion->otros, 'icono' => 'fa-plus-circle'],
          ];
          @endphp

          @foreach($secciones as $seccion)
          @if($seccion['campo'])
          <section class="mb-4">
            <h2 class="h3 font-weight-bold mb-3 text-secondary-custom">
              <i class="fas {{ $seccion['icono'] }} mr-2 opacity-75"></i>{{ $seccion['titulo'] }}
            </h2>
            <div class="article-body text-justify">
              {!! clean($seccion['campo']) !!}
            </div>
          </section>
          @endif
          @endforeach
        </div>
      </div>

      {{-- Columna Lateral: Ficha Técnica --}}
      <div class="col-lg-4">
        <div class="card shadow-sm border-0 sticky-top sidebar-infobox" style="top: 2rem;">

          <div class="card-header bg-dark-custom text-white font-weight-bold py-3 text-center">
            <i class="fas fa-landmark mr-2"></i> Ficha de Organización
          </div>

          <div class="card-body p-0">
            {{-- Escudo --}}
            <div class="p-3 border-bottom text-center bg-light">
              <img alt="Escudo de {{ $organizacion->nombre }}"
                class="img-fluid rounded shadow-sm"
                src="{{ asset('storage/escudos/' . $organizacion->escudo) }}"
                style="max-height: 350px; width: 100%; object-fit: cover;">
            </div>
            <ul class="list-group list-group-flush">

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Tipo</small>
                <span><i class="fas fa-tags mr-1"></i> {{ $organizacion->tipo->nombre ?? 'Desconocido' }}</span>
              </li>

              @if($organizacion->capital_nombre)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Capital</small>
                <span><i class="fa-solid fa-building-columns mr-1"></i> {{ $organizacion->capital_nombre }}</span>
              </li>
              @endif

              @if($organizacion->gentilicio)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Gentilicio</small>
                <span><i class="fas fa-user-tag mr-1"></i> {{ $organizacion->gentilicio }}</span>
              </li>
              @endif

              @if($organizacion->lider_id)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Líder</small>
                <span><i class="fas fa-chess-king mr-1"></i>
                  <a href="{{route('personajes.show', $organizacion->lider->id)}}">
                <img class="retrato-mini" src="{{ asset("storage/retratos/" . ($organizacion->lider->retrato ?? 'default.png')) }}" alt="Retrato de {{ $organizacion->lider->nombre }}">
                 {{ $organizacion->lider->nombre }}
              </a>
                </span>
              </li>
              @endif

              @if($organizacion->organizacion_padre_id)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Controlado por</small>
                <span>
                  <a href="{{route('organizaciones.show', $organizacion->organizacion_padre->id )}}">
                    <img class="retrato-mini" src="{{ asset("storage/escudos/" . ($organizacion->organizacion_padre->escudo ?? 'default.png')) }}" alt="Escudo de {{ $organizacion->organizacion_padre->nombre }}">
                    {{$organizacion->organizacion_padre->nombre}}
                  </a>
                </span>
              </li>
              @endif

              @if($organizacion->fundacion_id)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Fundación</small>
                <span><i class="fas fa-calendar-plus mr-1"></i> {{ $fundacion }}</span>
              </li>
              @endif

              @if($organizacion->disolucion_id)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Disolución</small>
                <span><i class="fas fa-calendar-times mr-1"></i> {{ $disolucion }}</span>
              </li>
              @endif

              @if($organizacion->religiones->isNotEmpty())
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Religiones presentes</small>
                @foreach($organizacion->religiones as $religion)
                <p class="ml-1 mr-2 mb-0">
                  <a href="{{route('religion.show', [$religion->id] )}}">
                    <img class="retrato-mini" src="{{ asset("storage/escudos/" . ($religion->escudo ?? 'default.png')) }}" alt="Escudo de {{ $religion->nombre }}">
                    {{$religion->nombre}}
                  </a>
                </p>
                @endforeach
              </li>
              @endif

              @if($organizacion->subordinates->isNotEmpty())
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Organizaciones subordinadas:</small>
                @foreach($organizacion->subordinates as $subordinate)
                <p class="ml-1 mr-2 mb-0">
                  <a href="{{route('organizaciones.show', [$subordinate->id] )}}">
                    <img class="retrato-mini" src="{{ asset("storage/escudos/" . ($subordinate->escudo ?? 'default.png')) }}" alt="Escudo de {{ $subordinate->nombre }}">
                    {{$subordinate->nombre}}
                  </a>
                </p>
                @endforeach
              </li>
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection