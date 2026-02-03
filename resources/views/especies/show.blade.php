@extends('layouts.index')

@section('title')
<title id="title">{{$especie->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('especies.index')}}" class="btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('especie.edit', ['id'=> $especie->id] )}}" class="btn btn-dark ml-2">Editar</a>
</li>
@endsection

@section('content')
<div class="container py-5">
  <div class="row mb-4">
    <div class="col-12 text-center text-md-left border-bottom pb-3">
      <h1 class="display-4 font-weight-bold text-dark mb-0">{{ $especie->nombre }}</h1>
      <p class="lead text-muted">{{ $especie->reino }} / {{ $especie->clase_taxonomica }}</p>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <div class="pr-lg-4">
        @php
        $secciones = [
        ['titulo' => 'Anatomía', 'campo' => $especie->anatomia, 'icono' => 'fa-dna'],
        ['titulo' => 'Alimentación', 'campo' => $especie->alimentacion, 'icono' => 'fa-utensils'],
        ['titulo' => 'Reproducción y Crecimiento', 'campo' => $especie->reproduccion, 'icono' => 'fa-seedling'],
        ['titulo' => 'Dimorfismo Sexual', 'campo' => $especie->dimorfismo_sexual, 'icono' => 'fa-venus-mars'],
        ['titulo' => 'Distribución y Hábitats', 'campo' => $especie->distribucion, 'icono' => 'fa-map-marked-alt'],
        ['titulo' => 'Habilidades', 'campo' => $especie->habilidades, 'icono' => 'fa-star'],
        ['titulo' => 'Domesticación', 'campo' => $especie->domesticacion, 'icono' => 'fa-home'],
        ['titulo' => 'Explotación', 'campo' => $especie->explotacion, 'icono' => 'fa-industry'],
        ['titulo' => 'Información Adicional', 'campo' => $especie->otros, 'icono' => 'fa-plus-circle'],
        ];
        @endphp

        @foreach($secciones as $seccion)
        @if($seccion['campo'])
        <section class="mb-3">
          <h3 class="h4 font-weight-bold text-dark mb-3">
            <i class="fas {{ $seccion['icono'] }} mr-2"></i>{{ $seccion['titulo'] }}
          </h3>
          <div class="text-justify lh-lg text-secondary card-rich-text">
            {!! $seccion['campo'] !!}
          </div>
        </section>
        @endif
        @endforeach
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm border-0 rounded-lg sticky-top" style="top: 2rem;">
        <div class="card-header bg-dark text-white font-weight-bold py-3">
          <i class="fas fa-clipboard-list mr-2"></i> Especificaciones
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            <x-especie-dato label="Reino" :value="$especie->reino" icon="fa-globe" />
            <x-especie-dato label="Clase" :value="$especie->clase_taxonomica" icon="fa-microscope" />
            <x-especie-dato label="Locomoción" :value="$especie->locomocion" icon="fa-running" />
            <x-especie-dato label="Social" :value="$especie->organizacion_social" icon="fa-users" />
            <x-especie-dato label="Vida Media" :value="$especie->edad" icon="fa-hourglass-start" />
            <x-especie-dato label="Peso" :value="$especie->peso" icon="fa-weight" />
            <x-especie-dato label="Altura" :value="$especie->altura" icon="fa-arrows-alt-v" />
            <x-especie-dato label="Longitud" :value="$especie->longitud" icon="fa-arrows-alt-h" />
            <x-especie-dato label="Dieta" :value="$especie->dieta" icon="fa-utensils" />
            <x-especie-dato label="Rareza" :value="$especie->rareza" icon="fa-star" />
          </ul>
        </div>
        <div class="card-footer bg-light border-0 py-3">
          <small class="text-muted d-block mb-1 text-uppercase font-weight-bold">Estatus de Conservación</small>
          <span class="badge badge-pill shadow-sm px-3 py-2 {{ $especie->estatus == 'Extinta' ? 'badge-danger' : 'badge-success' }}">
            {{ $especie->estatus }}
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .card-rich-text img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
  }

  .lh-lg {
    line-height: 1.8;
  }

  .w-30px {
    width: 30px;
  }
</style>
@endsection