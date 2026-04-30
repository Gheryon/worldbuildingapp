@extends('layouts.index')

@section('title')
<title id="title">{{$personaje->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('personajes.index')}}" class="btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('personajes.edit', $personaje->id) }}" class="btn btn-dark ml-2">Editar</a>
</li>
@endsection

@section('content')
<div class="container-fluid py-5 page">
  <div class="container">
    {{-- Encabezado Estilo Construcción --}}
    <div class="row mb-5">
      <div class="col-12 text-center text-md-left border-bottom-dark pb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <div>
            <h1 class="display-4 font-weight-bold mb-1 text-primary-custom">
              {{ $personaje->nombre }} {{ $personaje->nombre_familia }} {{ $personaje->apellidos }}
            </h1>
            <p class="lead text-secondary-custom font-italic">
              {{ $personaje->especie->nombre ?? 'Especie desconocida' }}
              @if($personaje->apodo)
              — "{{ $personaje->apodo }}"
              @endif
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      {{-- Columna Principal: Biografía y Detalles --}}
      <div class="col-lg-8">
        <div class="pr-lg-4">

          @if ($personaje->descripcion_corta)
          <section class="mb-5">
            <h2 class="h3 font-weight-bold mb-3 text-secondary-custom">
              <i class="fas fa-feather-alt mr-2 opacity-75"></i>Resumen
            </h2>
            <div class="article-body text-justify">
              {!! clean($personaje->descripcion_corta) !!}
            </div>
          </section>
          @endif

          @php
          $secciones = [
          ['titulo' => 'Descripción física', 'campo' => $personaje->descripcion_fisica, 'icono' => 'fa-user'],
          ['titulo' => 'Personalidad', 'campo' => $personaje->personalidad, 'icono' => 'fa-brain'],
          ['titulo' => 'Historia', 'campo' => $personaje->biografia, 'icono' => 'fa-scroll'],
          ['titulo' => 'Salud', 'campo' => $personaje->salud, 'icono' => 'fa-heartbeat'],
          ['titulo' => 'Deseos', 'campo' => $personaje->deseos, 'icono' => 'fa-star'],
          ['titulo' => 'Miedos', 'campo' => $personaje->miedos, 'icono' => 'fa-cloud-bolt'],
          ['titulo' => 'Habilidades mágicas', 'campo' => $personaje->magia, 'icono' => 'fa-wand-magic-sparkles'],
          ['titulo' => 'Educación', 'campo' => $personaje->educacion, 'icono' => 'fa-graduation-cap'],
          ['titulo' => 'Religión', 'campo' => $personaje->religion, 'icono' => 'fa-monument'],
          ['titulo' => 'Familia', 'campo' => $personaje->familia, 'icono' => 'fa-children'],
          ['titulo' => 'Política', 'campo' => $personaje->politica, 'icono' => 'fa-scale-balanced'],
          ['titulo' => 'Otros detalles', 'campo' => $personaje->otros, 'icono' => 'fa-plus-circle'],
          ];
          @endphp

          @foreach($secciones as $seccion)
          @if($seccion['campo'])
          <section class="mb-5">
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
            <i class="fas fa-id-card mr-2"></i> Ficha de Personaje
          </div>

          <div class="card-body p-0">
            {{-- Retrato --}}
            <div class="p-3 border-bottom text-center bg-light">
                <img alt="Retrato de {{ $personaje->nombre }}"
                     class="img-fluid rounded shadow-sm"
                     src="{{ asset('storage/retratos/' . ($personaje->retrato ?? 'default.png')) }}"
                     style="max-height: 350px; width: 100%; object-fit: cover;">
            </div>
            <ul class="list-group list-group-flush">

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Especie</small>
                <span><i class="fas fa-dna mr-1"></i>
                  <a href="{{ route('especie.show', $personaje->especie_id) }}" class="text-primary-custom">{{ $personaje->especie->nombre }}</a>
                </span>
              </li>

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Sexo</small>
                <span class="badge badge-light border">
                  <i class="fas fa-venus-mars mr-1"></i> {{ $personaje->sexo }}
                </span>
              </li>

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Edad</small>
                <span><i class="fas fa-birthday-cake mr-1"></i> {{ $edad }}</span>
              </li>

              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Cronología</small>
                <div class="mt-1">
                  <div class="mb-1">
                    <small class="text-muted"><i class="fa-solid fa-baby mr-1"></i> Nacimiento:</small><br>
                    <span>{{ $nacimiento ?? 'Sin determinar' }}</span>
                  </div>
                  @if($personaje->fallecimiento_id)
                  <div>
                    <small class="text-muted"><i class="fa-solid fa-skull-crossbones mr-1"></i> Fallecimiento:</small><br>
                    <span>{{ $fallecimiento }}</span>
                  </div>
                  @endif
                </div>
              </li>

              @if($personaje->lugar_nacimiento)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Origen</small>
                <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $personaje->lugar_nacimiento }}</span>
              </li>
              @endif

              @if($personaje->causa_fallecimiento)
              <li class="list-group-item bg-light">
                <small class="d-block text-danger text-uppercase font-weight-bold">Causa de muerte</small>
                <span class="text-muted small font-italic">{{ $personaje->causa_fallecimiento }}</span>
              </li>
              @endif

              @if($personaje->profesion)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Profesión / Cargo</small>
                <span class="badge badge-info"><i class="fas fa-briefcase mr-1"></i> {{ $personaje->profesion }}</span>
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