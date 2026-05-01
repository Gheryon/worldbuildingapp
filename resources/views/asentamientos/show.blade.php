@extends('layouts.index')
@section('title')
<title id="title">{{$asentamiento->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('asentamientos.index')}}" class="btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('asentamiento.edit', ['id'=> $asentamiento->id] )}}" class="btn btn-dark ml-2">Editar</a>
</li>
@endsection

@section('content')
<div class="container-fluid py-5 page">
  <div class="container">
    <div class="row mb-5">
      <div class="col-12 text-center text-md-left border-bottom-dark pb-3">
        <h1 class="display-4 font-weight-bold mb-1 text-primary-custom">{{ $asentamiento->nombre }}</h1>
        <p class="lead text-secondary-custom font-italic">
          {{ $asentamiento->tipo->nombre }}
        </p>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="pr-lg-4">
          @php
          $secciones = [
          ['titulo' => 'Descripción', 'campo' => $asentamiento->descripcion, 'icono' => 'fa-feather-alt'],
          ['titulo' => 'Historia', 'campo' => $asentamiento->historia, 'icono' => 'fa-scroll'],
          ['titulo' => 'Geografía', 'campo' => $asentamiento->geografia, 'icono' => 'fa-mountain'],
          ['titulo' => 'Clima', 'campo' => $asentamiento->clima, 'icono' => 'fa-cloud-sun-rain'],
          ['titulo' => 'Demografía', 'campo' => $asentamiento->demografia, 'icono' => 'fa-users'],
          ['titulo' => 'Cultura y Arquitectura', 'campo' => $asentamiento->cultura, 'icono' => 'fa-gopuram'],
          ['titulo' => 'Gobierno', 'campo' => $asentamiento->gobierno, 'icono' => 'fa-landmark'],
          ['titulo' => 'Infraestructura', 'campo' => $asentamiento->infraestructura, 'icono' => 'fa-tools'],
          ['titulo' => 'Defensas y Fuerzas Militares', 'campo' => $asentamiento->defensas ?? $asentamiento->ejercito, 'icono' => 'fa-chess-rook'],
          ['titulo' => 'Economía y Comercio', 'campo' => $asentamiento->economia, 'icono' => 'fa-coins'],
          ['titulo' => 'Recursos Naturales', 'campo' => $asentamiento->recursos, 'icono' => 'fa-leaf'],
          ['titulo' => 'Detalles Secretos', 'campo' => $asentamiento->ubicacion_detalles, 'icono' => 'fa-eye-slash'],
          ['titulo' => 'Otros', 'campo' => $asentamiento->otros, 'icono' => 'fa-plus-circle'],
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
            <i class="fas fa-info-circle mr-2"></i> Ficha Técnica
          </div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              @if(isset($asentamiento->tipo))
              <li class="list-group-item">
                <small class="d-block text-muted">Tipo de asentamiento</small>
                <span>{{$asentamiento->tipo->nombre}}</span>
              </li>
              @endif

              @if($asentamiento->poblacion)
              <li class="list-group-item">
                <small class="d-block text-muted">Población</small>
                <span><i class="fas fa-users-cog mr-1"></i> {{$asentamiento->poblacion}}</span>
              </li>
              @endif

              @if($asentamiento->gentilicio)
              <li class="list-group-item">
                <small class="d-block text-muted">Gentilicio</small>
                <span>{{$asentamiento->gentilicio}}</span>
              </li>
              @endif

              @if(isset($asentamiento->organizacion_id))
              <li class="list-group-item">
                <small class="d-block text-muted">Controlado por:</small>
                <a href="{{route('organizaciones.show', $asentamiento->organizacion_id)}}">{{$asentamiento->controlado_por->nombre}}</a>
              </li>
              @endif

               @if(isset($asentamiento->gobernante_id))
              <li class="list-group-item">
                <small class="d-block text-muted">Gobernado por:</small>
                <a href="{{route('personajes.show', $asentamiento->gobernante_id)}}">{{$asentamiento->gobernante->nombre}}</a>
              </li>
              @endif

              @if($asentamiento->nivel_riqueza)
              <li class="list-group-item">
                <small class="d-block text-muted">Nivel de Riqueza</small>
                <span><i class="fas fa-coins mr-1"></i> {{$asentamiento->nivel_riqueza}}</span>
              </li>
              @endif

              @if($asentamiento->recurso_principal)
              <li class="list-group-item">
                <small class="d-block text-muted">Recurso Principal</small>
                <span><i class="fas fa-gem mr-1"></i> {{$asentamiento->recurso_principal}}</span>
              </li>
              @endif

              @if($asentamiento->fundacion_id != 0)
              <li class="list-group-item">
                <small class="d-block text-muted"><i class="fas fa-calendar-plus mr-1"></i>Fundación</small>
                <span>{{$fundacion}}</span>
              </li>
              @endif

              @if($asentamiento->disolucion_id != 0)
              <li class="list-group-item">
                <small class="d-block text-muted"><i class="fas fa-calendar-times mr-1"></i>Disolución</small>
                <span>{{$disolucion}}</span>
              </li>
              @endif
            </ul>
          </div>
          <div class="card-footer bg-light border-0 py-3 text-center">
          <small class="text-muted d-block mb-2 text-uppercase font-weight-bold">Estatus</small>
          @php
            // Mapeo de clases de Bootstrap según el estatus
            $statusClasses = [
                'Abandonado' => 'badge-warning text-dark',
                'En ruinas'  => 'badge-danger',
                'En uso'     => 'badge-success',
                'Olvidado'   => 'badge-secondary'
            ];
            $currentClass = $statusClasses[$asentamiento->estatus] ?? 'badge-dark';
          @endphp
          <span class="badge badge-pill shadow-sm px-4 py-2 {{ $currentClass }}">
            <i class="fas fa-history mr-1"></i> {{ $asentamiento->estatus }}
          </span>
        </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- /.content -->
@endsection