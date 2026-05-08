@extends('layouts.index')

@section('title')
<title id="title">{{$religion->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('religiones.index')}}" class="btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('religiones.edit', $religion->id)}}" class="btn btn-dark ml-2">Editar</a>
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
            <h1 class="display-4 font-weight-bold mb-1 text-primary-custom">{{ $religion->nombre }}</h1>
            @if($religion->lema)
            <p class="lead text-secondary-custom font-italic">
              "{{ $religion->lema }}"
            </p>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      {{-- Columna Principal: Contenido --}}
      <div class="col-lg-8">
        <div class="pr-lg-4">

          @if ($religion->descripcion)
          <section class="mb-5">
            <h2 class="h3 font-weight-bold mb-3 text-secondary-custom">
              <i class="fas fa-feather-alt mr-2 opacity-75"></i>Resumen
            </h2>
            <div class="article-body text-justify">
              {!! clean($religion->descripcion) !!}
            </div>
          </section>
          @endif

          @php
          $secciones = [
          ['titulo' => 'Historia', 'campo' => $religion->historia, 'icono' => 'fa-scroll'],
          ['titulo' => 'Cosmología', 'campo' => $religion->cosmologia, 'icono' => 'fa-sun'],
          ['titulo' => 'Doctrina', 'campo' => $religion->doctrina, 'icono' => 'fa-book-open'],
          ['titulo' => 'Lugares y objetos sagrados', 'campo' => $religion->sagrado, 'icono' => 'fa-place-of-worship'],
          ['titulo' => 'Clase sacerdotal', 'campo' => $religion->clase_sacerdotal, 'icono' => 'fa-hands-praying'],
          ['titulo' => 'Fiestas y rituales importantes', 'campo' => $religion->fiestas, 'icono' => 'fa-masks-theater'],
          ['titulo' => 'Elementos sobrenaturales', 'campo' => $religion->sobrenatural, 'icono' => 'fa-dragon'],
          ['titulo' => 'Influencia política', 'campo' => $religion->politica, 'icono' => 'fa-crown'],
          ['titulo' => 'Estructura religiosa', 'campo' => $religion->estructura, 'icono' => 'fa-sitemap'],
          ['titulo' => 'Sectas', 'campo' => $religion->sectas, 'icono' => 'fa-users-cog'],
          ['titulo' => 'Otros detalles', 'campo' => $religion->otros, 'icono' => 'fa-plus-circle'],
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
            <i class="fas fa-monument mr-2"></i> Ficha de Religión
          </div>

          <div class="card-body p-0">
            {{-- Escudo --}}
            <div class="p-3 border-bottom text-center bg-light">
              <img alt="Escudo de {{ $religion->nombre }}"
                class="img-fluid rounded shadow-sm"
                src="{{ asset('storage/escudos/' . $religion->escudo) }}"
                style="max-height: 350px; width: 100%; object-fit: cover;">
            </div>
            <ul class="list-group list-group-flush">

              @if($religion->estatus_legal)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Estatus legal</small>
                <span><i class="fas fa-gavel mr-1"></i> {{ $religion->estatus_legal }}</span>
              </li>
              @endif

              @if ($religion->lema)
              <li class="list-group-item">
              <small class="d-block text-muted text-uppercase font-weight-bold">Lema:</small>
              <span><i class="fas fa-feather-alt mr-2"></i>{{$religion->lema}}</span>
              </li>
              @endif

              @if($religion->tipo_teismo)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Tipo de teísmo</small>
                <span><i class="fas fa-dharmachakra mr-1"></i> {{ $religion->tipo_teismo->label() }}</span>
              </li>
              @endif

              @if($religion->deidades)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Deidades</small>
                <span><i class="fas fa-crown mr-1"></i> {{ $religion->deidades }}</span>
              </li>
              @endif

              @if($religion->fundacion_id)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Fundación</small>
                <span><i class="fas fa-calendar-plus mr-1"></i> {{ $fundacion }}</span>
              </li>
              @endif

              @if($religion->disolucion_id)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Disolución</small>
                <span><i class="fas fa-calendar-times mr-1"></i> {{ $disolucion }}</span>
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