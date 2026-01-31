@extends('layouts.index')

@section('title')
<title id="title">{{$religion->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('religiones.index')}}" class="btn btn-dark">Volver</a>
  <a href="{{route('religion.edit', ['id'=> $religion->id] )}}" class="btn btn-dark ml-2">Editar</a>
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
              {{ $religion->nombre }}
            </h1>
          </div>
          <div class="card-body">
            @if (isset($religion->descripcion))
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-align-left mr-2"></i>Descripción breve</h2>
            <div class="ml-4 mb-3">{!!$religion->descripcion!!}</div>
            @endif

            @if ($religion->historia)
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-scroll mr-2"></i>Historia</h2>
            <div class="ml-4 mb-3">{!! $religion->historia !!}</div>
            @endif

            @if ($religion->cosmologia)
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-sun mr-2"></i>Cosmología</h2>
            <p class="ml-4 mb-3">{!!$religion->cosmologia!!}</p>
            @endif

            @if (isset($religion->doctrina))
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-book-open mr-2"></i>Doctrina</h2>
            <p class="ml-4 mb-3">{!!$religion->doctrina!!}</p>
            @endif

            @if ($religion->sagrado)
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-place-of-worship mr-2"></i>Lugares y objetos sagrados</h2>
            <p class="ml-4 mb-3">{!!$religion->sagrado!!}</p>
            @endif

            @if ($religion->clase_sacerdotal)
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-hands-praying mr-2"></i>Clase sacerdotal</h2>
            <p class="ml-4 mb-3">{!!$religion->clase_sacerdotal!!}</p>
            @endif

            @if ($religion->fiestas)
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-masks-theater mr-2"></i>Fiestas y rituales importantes</h2>
            <p class="ml-4 mb-3">{!!$religion->fiestas!!}</p>
            @endif

            @if ($religion->politica)
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-crown mr-2"></i>Influencia política</h2>
            <p class="ml-4 mb-3">{!!$religion->politica!!}</p>
            @endif

            @if ($religion->estructura)
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-sitemap mr-2"></i>Estructura</h2>
            <p class="ml-4 mb-3">{!!$religion->estructura!!}</p>
            @endif

            @if ($religion->sectas)
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-users-cog mr-2"></i>Sectas</h2>
            <p class="ml-4 mb-3">{!!$religion->sectas!!}</p>
            @endif

            @if ($religion->otros)
            <h2 class="border-bottom pb-2 mb-3"><i class="fas fa-plus-circle mr-2"></i>Otros</h2>
            <p class="ml-4 mb-3">{!!$religion->otros!!}</p>
            @endif
          </div>
        </div>

      </div>

      {{-- Columna lateral: ficha técnica --}}
      <div class="col-md-4">
        <div class="card card-dark">
          <div class="card-header">
            <h3 class="card-title">Ficha de religión</h3>
          </div>
          <div class="card-body" id="content-right">
            <h3>Escudo</h3>
            <div class="row">
              <img alt="escudo" id="escudo" class="img-thumbnail shadow-sm mb-3" src="{{asset("storage/escudos/{$religion->escudo}")}}" width="300" height="300">
            </div>

            @if (isset($religion->lema))
            <strong><i class="fas fa-feather-alt mr-2"></i>Lema:</strong>
            <p class="text-muted">{{$religion->lema}}</p>
            @endif

            @if ($religion->estatus_legal)
            <strong><i class="fas fa-gavel mr-1"></i>Estatus legal:</strong>
            <p class="text-muted">{{ $religion->estatus_legal }}</p>
            @endif

            @if ($religion->tipo_teismo)
            <strong><i class="fas fa-dharmachakra mr-1"></i>Tipo de teísmo:</strong>
            <p class="text-muted">{{ $religion->tipo_teismo->label() }}</p>
            @endif
            
            @if ($religion->deidades)
            <strong><i class="fas fa-crown mr-1"></i>Deidades:</strong>
            <p class="text-muted">{{ $religion->deidades }}</p>
            @endif

            @if ($religion->fundacion_id)
            <strong><i class="fas fa-calendar-plus mr-1"></i>Fecha de fundación:</strong>
            <p class="text-muted">{{ $fundacion }}</p>
            @endif

            @if ($religion->disolucion_id)
            <strong><i class="fas fa-calendar-times mr-1"></i>Disolución:</strong>
            <p class="text-muted">{{ $disolucion}}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection