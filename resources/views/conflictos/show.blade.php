@extends('layouts.index')

@section('title')
<title id="title">{{$conflicto->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('conflictos.index')}}" class="btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('conflicto.edit', ['id'=> $conflicto->id] )}}" class="btn btn-dark ml-2">Editar</a>
</li>
@endsection

@section('content')
<div class="container-fluid py-5 page">
  <div class="container">
    {{-- Encabezado Principal --}}
    <div class="row mb-5">
      <div class="col-12 text-center text-md-left border-bottom-dark pb-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <div>
            <h1 class="display-4 font-weight-bold mb-1 text-primary-custom">
              {{ $conflicto->nombre }}
              @if($conflicto->es_conflicto_magico)
              <small><i class="fas fa-magic text-primary-custom ml-2" title="Conflicto Mágico"></i></small>
              @endif
            </h1>
            <p class="lead text-secondary-custom font-italic">
              {{ $conflicto->tipoConflicto->nombre ?? 'Conflicto' }} en {{ $conflicto->tipo_localizacion ?? 'Ubicación desconocida' }}
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      {{-- Columna Izquierda: Contenido Extenso --}}
      <div class="col-lg-7">
        <div class="pr-lg-4">
          @php
          $secciones = [
          ['titulo' => 'Descripción', 'campo' => $conflicto->descripcion, 'icono' => 'fa-align-left'],
          ['titulo' => 'Preludio y causas', 'campo' => $conflicto->preludio, 'icono' => 'fa-history'],
          ['titulo' => 'Desarrollo del conflicto', 'campo' => $conflicto->desarrollo, 'icono' => 'fa-fist-raised'],
          ['titulo' => 'Resultado y finalización', 'campo' => $conflicto->resultado, 'icono' => 'fa-flag-checkered'],
          ['titulo' => 'Consecuencias geopolíticas', 'campo' => $conflicto->consecuencias, 'icono' => 'fa-monument'],
          ['titulo' => 'Fenómenos naturales', 'campo' => $conflicto->fenomenos_naturales, 'icono' => 'fa-cloud-showers-heavy'],
          ['titulo' => 'Elementos mágicos y sobrenaturales', 'campo' => $conflicto->hechizos_decisivos, 'icono' => 'fa-hat-wizard'],
          ['titulo' => 'Otros detalles', 'campo' => $conflicto->otros, 'icono' => 'fa-plus-circle'],
          ];
          @endphp

          @foreach($secciones as $seccion)
          @if($seccion['campo'] && $seccion['campo'] !== '<p><br></p>')
          <section class="mb-5">
            <h2 class="h4 font-weight-bold mb-3 text-secondary-custom">
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

      {{-- Columna Derecha: Ficha Técnica (Estilo Wikipedia) --}}
      <div class="col-lg-5">
        <div class="card shadow-sm border-0 sticky-top sidebar-infobox" style="top: 2rem;">
          <div class="card-header bg-dark-custom text-white text-center font-weight-bold py-3">
            {{ $conflicto->nombre }}
          </div>

          <div class="card-body p-0">
            <table class="table table-sm mb-0 small">
              <tbody>
                <tr>
                  <th class="bg-light">Tipo</th>
                  <td><strong>{{ $conflicto->tipoConflicto->nombre ?? 'Guerra' }}</strong></td>
                </tr>
                <tr>
                  <th class="bg-light w-50">Fecha inicio</th>
                  <td class="w-50">{{ $fecha_inicio ?? 'Desconocida' }}</td>
                </tr>
                <tr>
                  <th class="bg-light w-50">Fecha final</th>
                  <td class="w-50">{{ $fecha_fin ?? 'Desconocida' }}</td>
                </tr>
                <tr>
                  <th class="bg-light">Lugar</th>
                  <td>{{ $conflicto->tipo_localizacion }}</td>
                </tr>
                <tr>
                  <th class="bg-light">Resultado</th>
                  <td><strong>{{ $conflicto->vencedor_texto ?? 'Indeciso' }}</strong></td>
                </tr>
              </tbody>
            </table>

            {{-- Sección de Beligerantes --}}
            <div class="bg-dark-custom text-white text-center py-1 small font-weight-bold text-uppercase">
              Beligerantes
            </div>

            <div class="row no-gutters border-bottom">
              {{-- Atacantes --}}
              <div class="col-6 border-right px-2 py-2">
                <p class="text-center font-weight-bold border-bottom mb-2">Atacantes</p>
                <ul class="list-unstyled mb-0">
                  @foreach($conflicto->organizaciones->where('pivot.lado', 'atacante') as $org)
                  <li class="mb-1">
                    <a href="{{route('organizacion.show', $org->id)}}" class="personaje-link font-weight-bold">
                      <img class="retrato-mini" src="{{ asset("storage/escudos/" . ($org->escudo ?? 'default.png')) }}" alt="Escudo de {{ $org->nombre }}">
                      {{ $org->nombre }}
                    </a>
                  </li>
                  @endforeach
                  @foreach($conflicto->personajes->where('pivot.lado', 'atacante') as $per)
                  <li class="small ml-2">
                    <a href="{{route('personajes.show', $per->id)}}" class="personaje-link">
                      <img class="retrato-mini" src="{{ asset("storage/retratos/" . ($per->retrato ?? 'default.png')) }}" alt="Retrato de {{ $per->nombre }}">
                      {{ $per->nombre }}
                    </a>
                  </li>
                  @endforeach
                </ul>
              </div>
              {{-- Defensores --}}
              <div class="col-6 px-2 py-2">
                <p class="text-center font-weight-bold border-bottom mb-2">Defensores</p>
                <ul class="list-unstyled mb-0">
                  @foreach($conflicto->organizaciones->where('pivot.lado', 'defensor') as $org)
                  <li class="mb-1">
                    <a href="{{route('organizacion.show', $org->id)}}" class="personaje-link font-weight-bold">
                      <img class="retrato-mini" src="{{ asset("storage/escudos/" . ($org->escudo ?? 'default.png')) }}" alt="Escudo de {{ $org->nombre }}">
                      {{ $org->nombre }}
                    </a>
                  </li>
                  @endforeach
                  @foreach($conflicto->personajes->where('pivot.lado', 'defensor') as $per)
                  <li class="small ml-2">
                    <a href="{{route('personajes.show', $per->id)}}" class="personaje-link">
                      <img class="retrato-mini" src="{{ asset("storage/retratos/" . ($per->retrato ?? 'default.png')) }}" alt="Retrato de {{ $per->nombre }}">
                      {{ $per->nombre }}
                    </a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>

            {{-- Atributos de Guerra --}}
            <ul class="list-group list-group-flush small">
              <li class="list-group-item bg-light text-center font-weight-bold py-1 text-uppercase">
                Información Adicional
              </li>

              @if($conflicto->unidades_especiales)
              <li class="list-group-item">
                <small class="d-block text-muted text-uppercase font-weight-bold">Unidades Destacadas</small>
                <div class="article-body-mini">{!! clean($conflicto->unidades_especiales) !!}</div>
              </li>
              @endif

              @if($conflicto->es_conflicto_magico)
              <li class="list-group-item bg-magic-soft">
                <small class="d-block text-primary-custom text-uppercase font-weight-bold"><i class="fas fa-magic"></i> Factor Mágico</small>
                <div class="mt-1 small">
                  <strong>Artefactos o armas:</strong> {!! clean($conflicto->armas_magicas_empleadas) !!}
                </div>
              </li>
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .bg-magic-soft {
    background-color: rgba(144, 11, 11, 0.05);
  }

  .article-body-mini {
    font-size: 0.85rem;
    line-height: 1.4;
  }

  .sidebar-infobox th {
    font-size: 0.85rem;
    vertical-align: middle;
  }

  .sidebar-infobox td {
    font-size: 0.85rem;
  }
</style>
@endsection