@extends('layouts.index')

@section('title')
<title id="title">{{$construccion->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('construcciones.index')}}" class="btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('construccion.edit', ['id'=> $construccion->id] )}}" class="btn btn-dark ml-2">Editar</a>
</li>
@endsection

@section('content')
<div class="container-fluid py-5 page">
  <div class="container">
    <div class="row mb-5">
      <div class="col-12 text-center text-md-left border-bottom-dark pb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <div>
            <h1 class="display-4 font-weight-bold mb-1 text-primary-custom">{{ $construccion->nombre }}</h1>
            <p class="lead text-secondary-custom font-italic">
              {{ $construccion->tipo->nombre ?? 'Edificación' }}
              @if($construccion->asentamiento)
              en <a href="{{ route('asentamiento.show', $construccion->asentamiento_id) }}" class="font-weight-bold text-primary-custom">{{ $construccion->asentamiento->nombre }}</a>
              @endif
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="pr-lg-4">
          @php
          $secciones = [
          ['titulo' => 'Descripción', 'campo' => $construccion->descripcion_breve, 'icono' => 'fa-feather-alt'],
          ['titulo' => 'Aspecto Visual', 'campo' => $construccion->aspecto, 'icono' => 'fa-eye'],
          ['titulo' => 'Historia y Origen', 'campo' => $construccion->historia, 'icono' => 'fa-scroll'],
          ['titulo' => 'Arquitectura y Diseño', 'campo' => $construccion->arquitectura, 'icono' => 'fa-archway'],
          ['titulo' => 'Propósito Original', 'campo' => $construccion->proposito, 'icono' => 'fa-bullseye'],
          ['titulo' => 'Materiales y Técnicas', 'campo' => $construccion->materiales_principales, 'icono' => 'fa-hammer'],
          ['titulo' => 'Materiales Exóticos', 'campo' => $construccion->materiales_exoticos, 'icono' => 'fa-gem'],
          ['titulo' => 'Propiedades Mágicas', 'campo' => $construccion->propiedades_magicas, 'icono' => 'fa-sparkles'],
          ['titulo' => 'Simbolismo e Importancia', 'campo' => $construccion->simbolismo, 'icono' => 'fa-monument'],
          ['titulo' => 'Rutas de Acceso', 'campo' => $construccion->rutas_acceso, 'icono' => 'fa-map-signs'],
          ['titulo' => 'Otros Detalles', 'campo' => $construccion->otros, 'icono' => 'fa-plus-circle'],
          ];
          @endphp

          @foreach($secciones as $seccion)
          @if($seccion['campo'])
          <section class="mb-5">
            <h2 class="h3 font-weight-bold mb-3 text-secondary-custom">
              <i class="fas {{ $seccion['icono'] }} mr-2 opacity-75"></i>{{ $seccion['titulo'] }}
            </h2>
            <div class="article-body text-justify">
              {{-- Uso de clean() para HTMLPurifier en campos RichText --}}
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
            <i class="fas fa-info-circle mr-2"></i> Ficha Técnica
          </div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">

              @if($construccion->tipo)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Tipo de construcción</small>
                <span><i class="fas fa-tags mr-1"></i> {{ $construccion->tipo->nombre }}</span>
              </li>
              @endif

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Estatus</small>
                @php
                $colorEstatus = match($construccion->estatus) {
                'En pie', 'Habitado' => 'badge-success',
                'Destruido', 'En ruinas' => 'badge-danger',
                'Abandonado', 'Olvidado' => 'badge-secondary',
                'En construcción' => 'badge-info',
                default => 'badge-light border'
                };
                @endphp
                <span class="badge {{ $colorEstatus }}"><i class="fas fa-flag mr-1"></i> {{ $construccion->estatus ?? 'N/A' }}</span>
              </li>

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Nivel deterioro</small>
                @php
                $colorDeterioro = match($construccion->nivel_deterioro) {
                'Ninguno', 'Bajo' => 'badge-success',
                'Medio' => 'badge-warning',
                'Alto', 'Irreversible' => 'badge-danger',
                default => 'badge-light border'
                };
                @endphp
                <span class="badge {{ $colorDeterioro }}"><i class="fas fa-tools mr-1"></i> {{ $construccion->nivel_deterioro ?? 'No especificado' }}</span>
              </li>

              @if($construccion->altitud)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Altura</small>
                <span><i class="fas fa-arrows-alt-v mr-1"></i> {{ $construccion->altitud }}</span>
              </li>
              @endif

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Ubicación</small>
                @if($construccion->asentamiento)
                <span><i class="fas fa-map-marker-alt mr-1"></i> <a href="{{ route('asentamiento.show', $construccion->asentamiento_id) }}">{{ $construccion->asentamiento->nombre }}</a></span>
                @else
                <span class="text-muted"><i class="fas fa-map-marker-alt mr-1"></i> Desconocida</span>
                @endif
              </li>

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Información de acceso</small>

                {{-- Dificultad con código de colores --}}
                @php
                $colorAcceso = match($construccion->dificultad_acceso) {
                'Libre', 'Fácil' => 'badge-success',
                'Moderada' => 'badge-warning',
                'Difícil', 'Extrema' => 'badge-danger',
                default => 'badge-light border'
                };
                @endphp
                <div class="mb-2">
                  <span class="badge {{ $colorAcceso }} p-1 px-2">
                    <i class="fas fa-hiking mr-1"></i> {{ $construccion->dificultad_acceso ?? 'Sin especificar' }}
                  </span>
                </div>

                {{-- Acceso Público y Temporal con Badges e iconos de candado --}}
                <div class="d-flex flex-wrap gap-2">
                  @if($construccion->acceso_publico)
                  <span class="badge badge-success">
                    <i class="fas fa-lock-open mr-1"></i> Público
                  </span>
                  @else
                  <span class="badge badge-danger">
                    <i class="fas fa-lock mr-1"></i> Privado/Restringido
                  </span>
                  @endif

                  @if($construccion->acceso_temporal)
                  <span class="badge badge-info">
                    <i class="fas fa-hourglass-half mr-1"></i> Acceso Temporal
                  </span>
                  @endif
                </div>
              </li>

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Atributos especiales</small>
                <div class="mt-1">
                  @if($construccion->tiene_magia_inherente)
                  <span class="badge badge-primary mr-1"><i class="fas fa-magic mr-1"></i> Magia inherente</span>
                  @endif
                  @if($construccion->tecnologia_perdida)
                  <span class="badge badge-warning"><i class="fas fa-microchip mr-1"></i> Tecnología perdida</span>
                  @endif
                </div>
              </li>

              <div class="list-group-item">
                <strong><i class="fas fa-calendar-plus mr-1"></i>Fecha de construcción:</strong>
                <p class="text-muted">{{ $fecha_construccion ?? 'Sin especificar' }}</p>
                <strong><i class="fas fa-calendar-times mr-1"></i>Fecha de destrucción:</strong>
                <p class="text-muted">{{ $fecha_destruccion ?? 'Sin especificar' }}</p>
              </div>

            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection