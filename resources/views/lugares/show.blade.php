@extends('layouts.index')

@section('title')
<title id="title">{{$lugar->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('lugares.index')}}" class="btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
<a href="{{route('lugar.edit', ['id'=> $lugar->id] )}}" class="btn btn-dark ml-2">Editar</a>
</li>
@endsection

@section('content')
<div class="container-fluid py-5 page">
  <div class="container">
    <div class="row mb-5">
      <div class="col-12 text-center text-md-left border-bottom-dark pb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h1 class="display-4 font-weight-bold mb-1 text-primary-custom">{{ $lugar->nombre }}</h1>
                <p class="lead text-secondary-custom font-italic">
                  {{ $lugar->tipo->nombre ?? 'Lugar Desconocido' }}
                </p>
            </div>
            @if($lugar->es_secreto)
            <span class="badge badge-warning p-2 shadow-sm">
                <i class="fas fa-eye-slash mr-1"></i> Ubicación Secreta
            </span>
            @endif
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="pr-lg-4">
          @php
          $secciones = [
            ['titulo' => 'Descripción', 'campo' => $lugar->descripcion_breve, 'icono' => 'fa-feather-alt'],
            ['titulo' => 'Historia', 'campo' => $lugar->historia, 'icono' => 'fa-scroll'],
            ['titulo' => 'Geografía', 'campo' => $lugar->geografia, 'icono' => 'fa-mountain'],
            ['titulo' => 'Ecosistema', 'campo' => $lugar->ecosistema, 'icono' => 'fa-leaf'],
            ['titulo' => 'Clima', 'campo' => $lugar->clima, 'icono' => 'fa-cloud-sun'],
            ['titulo' => 'Fenómenos Únicos', 'campo' => $lugar->fenomeno_unico, 'icono' => 'fa-magic'],
            ['titulo' => 'Flora y Fauna', 'campo' => $lugar->flora_fauna, 'icono' => 'fa-paw'],
            ['titulo' => 'Recursos', 'campo' => $lugar->recursos, 'icono' => 'fa-gem'],
            ['titulo' => 'Rumores y Leyendas', 'campo' => $lugar->rumores, 'icono' => 'fa-comment-dots'],
            ['titulo' => 'Otros Detalles', 'campo' => $lugar->otros, 'icono' => 'fa-plus-circle'],
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

      <div class="col-lg-4">
        <div class="card shadow-sm border-0 sticky-top sidebar-infobox" style="top: 2rem;">
          <div class="card-header bg-dark-custom text-white font-weight-bold py-3">
            <i class="fas fa-map-marked-alt mr-2"></i> Información del Lugar
          </div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              
              @if($lugar->otros_nombres)
              <li class="list-group-item">
                <small class="d-block text-muted">Otros nombres</small>
                <span><i class="fas fa-tags mr-1"></i> {{ $lugar->otros_nombres }}</span>
              </li>
              @endif

              @if(isset($lugar->tipo))
              <li class="list-group-item">
                <small class="d-block text-muted">Tipo de lugar</small>
                <span>{{ $lugar->tipo->nombre }}</span>
              </li>
              @endif

              @if($lugar->dificultad_acceso)
              <li class="list-group-item">
                <small class="d-block text-muted">Dificultad de acceso</small>
                <span><i class="fas fa-hiking mr-1"></i> {{ $lugar->dificultad_acceso }}</span>
              </li>
              @endif

              @if($lugar->estacionalidad)
              <li class="list-group-item">
                <small class="d-block text-muted">Estacionalidad</small>
                <span><i class="fas fa-calendar-alt mr-1"></i> {{ $lugar->estacionalidad }}</span>
              </li>
              @endif

              @if($lugar->tipo_peligro)
              <li class="list-group-item">
                <small class="d-block text-muted">Tipo de peligro</small>
                <span><i class="fas fa-skull mr-1"></i> {{ $lugar->tipo_peligro }}</span>
              </li>
              @endif

            </ul>
          </div>

          <div class="card-footer bg-light border-0 py-3 text-center">
            <small class="text-muted d-block mb-2 text-uppercase font-weight-bold">Nivel de Peligro</small>
            @php
              $peligro = $lugar->peligro_config; // Laravel convierte camelCase (peligroConfig) a snake_case automáticamente
              // Generamos el HTML del icono una sola vez
              $iconHtml = '<i class="fas fa-exclamation-triangle mx-1"></i>';
              // Creamos el bloque de iconos repetido según la configuración
              $icons = str_repeat($iconHtml, $peligro->icons);
            @endphp
            <span class="badge badge-pill shadow-sm px-4 py-2 {{ $peligro->class }}" style="font-size: 0.9rem;">
              {!! $icons !!} {{ $lugar->nivel_peligro ?? 'Desconocido' }} {!! $icons !!}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection