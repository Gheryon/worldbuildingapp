@extends('layouts.index')

@section('title')
<title id="title">{{$cultura->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('culturas.index')}}" class="btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('culturas.edit', $cultura->id )}}" class="btn btn-dark ml-2">Editar</a>
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
            <h1 class="display-4 font-weight-bold mb-1 text-primary-custom">{{ $cultura->nombre }}</h1>
            <p class="lead text-secondary-custom font-italic">
              {{ $cultura->gentilicio ?? 'Gentilicio desconocido' }}
              @if($cultura->estatus)
              — <span class="badge badge-{{ $cultura->estatus?->value == 'extinta' ? 'danger' : ($cultura->estatus?->value == 'en declive' ? 'warning' : 'success') }}">{{ $cultura->estatus?->label() }}</span>
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

          @if ($cultura->descripcion_breve)
          <section class="mb-5">
            <h2 class="h3 font-weight-bold mb-3 text-secondary-custom">
              <i class="fas fa-feather-alt mr-2 opacity-75"></i>Resumen
            </h2>
            <div class="article-body text-justify">
              {!! clean($cultura->descripcion_breve) !!}
            </div>
          </section>
          @endif

          @php
          $secciones = [
          ['titulo' => 'Distribución geográfica', 'campo' => $cultura->distribucion_geografica, 'icono' => 'fa-map'],
          ['titulo' => 'Idioma y escritura', 'campo' => $cultura->idioma, 'icono' => 'fa-language'],
          ['titulo' => 'Historia', 'campo' => $cultura->historia, 'icono' => 'fa-scroll'],
          ['titulo' => 'Estructura social', 'campo' => $cultura->estructura_social, 'icono' => 'fa-sitemap'],
          ['titulo' => 'Roles de género', 'campo' => $cultura->roles_genero, 'icono' => 'fa-venus-mars'],
          ['titulo' => 'Cosmovisión', 'campo' => $cultura->cosmovision, 'icono' => 'fa-eye'],
          ['titulo' => 'Fiestas y festivales', 'campo' => $cultura->fiestas, 'icono' => 'fa-calendar-alt'],
          ['titulo' => 'Tabúes', 'campo' => $cultura->tabues, 'icono' => 'fa-ban'],
          ['titulo' => 'Símbolos culturales', 'campo' => $cultura->simbolos, 'icono' => 'fa-icons'],
          ['titulo' => 'Ética y código moral', 'campo' => $cultura->etica, 'icono' => 'fa-scale-balanced'],
          ['titulo' => 'Vestimenta tradicional', 'campo' => $cultura->vestimenta, 'icono' => 'fa-vest'],
          ['titulo' => 'Gastronomía', 'campo' => $cultura->gastronomia, 'icono' => 'fa-utensils'],
          ['titulo' => 'Arquitectura', 'campo' => $cultura->arquitectura, 'icono' => 'fa-building-columns'],
          ['titulo' => 'Arte y música', 'campo' => $cultura->arte_musica, 'icono' => 'fa-music'],
          ['titulo' => 'Tecnología', 'campo' => $cultura->tecnologia, 'icono' => 'fa-microchip'],
          ['titulo' => 'Educación', 'campo' => $cultura->educacion, 'icono' => 'fa-graduation-cap'],
          ['titulo' => 'Otros detalles', 'campo' => $cultura->otros, 'icono' => 'fa-plus-circle'],
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
        <x-reference-images-gallery :imagenes="$cultura->imagenes" :entityId="$cultura->id" />
      </div>

      {{-- Columna Lateral: Ficha Técnica --}}
      <div class="col-lg-4">
        <div class="card shadow-sm border-0 sticky-top sidebar-infobox" style="top: 2rem;">

          <div class="card-header bg-dark-custom text-white font-weight-bold py-3 text-center">
            <i class="fas fa-globe mr-2"></i> Ficha de Cultura
          </div>

          <div class="card-body p-0">
            <ul class="list-group list-group-flush">

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Nombre</small>
                <span><i class="fas fa-tag mr-1"></i> {{ $cultura->nombre }}</span>
              </li>

              @if($cultura->gentilicio)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Gentilicio</small>
                <span><i class="fas fa-user-tag mr-1"></i> {{ $cultura->gentilicio }}</span>
              </li>
              @endif

              @if($cultura->categoria)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Importancia</small>
                <span><i class="fas fa-layer-group mr-1"></i> {{ ucfirst($cultura->categoria) }}</span>
              </li>
              @endif

              @if($cultura->tipo_territorio)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Tipo de territorio</small>
                <span><i class="fas fa-tree mr-1"></i> {{ $cultura->tipo_territorio?->label() }}</span>
              </li>
              @endif

              @if($cultura->unidad_familiar)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Unidad familiar</small>
                <span><i class="fas fa-people-roof mr-1"></i> {{ ucfirst($cultura->unidad_familiar) }}</span>
              </li>
              @endif

              @if($cultura->actitud_magia)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Actitud hacia la magia</small>
                <span><i class="fas fa-wand-magic-sparkles mr-1"></i> {{ $cultura->actitud_magia?->label() }}</span>
              </li>
              @endif

              @if($cultura->actitud_forasteros)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Actitud hacia forasteros</small>
                <span><i class="fas fa-people-arrows mr-1"></i> {{ $cultura->actitud_forasteros?->label() }}</span>
              </li>
              @endif

              @if($cultura->madre_id)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Cultura madre</small>
                <span>
                  <a href="{{route('culturas.show', $cultura->cultura_madre->id )}}">
                    {{$cultura->cultura_madre->nombre}}
                  </a>
                </span>
              </li>
              @endif

              @if($cultura->fundacion_id)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Fundación</small>
                <span><i class="fas fa-calendar-plus mr-1"></i> {{ $fundacion }}</span>
              </li>
              @endif

              @if($cultura->disolucion_id)
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
