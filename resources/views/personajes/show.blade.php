@extends('layouts.index')

@section('title')
<title id="title">{{$personaje->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('personajes.index')}}" class="btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('personaje.edit', ['id'=> $personaje->id] )}}" class="btn btn-dark ml-2">Editar</a>
</li>
@endsection

@section('content')
<section class="content mt-4">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8">
        <div class="card card-outline card-dark">
          <div class="card-header">
            <h1 class="card-title" style="font-size: 2rem;">
              {{ $personaje->nombre }} {{ $personaje->nombre_familia }} {{ $personaje->apellidos }}
            </h1>
          </div>
          <div class="card-body">
            @if ($personaje->descripcion_short)
            <h3>Descripción breve</h3>
            <div class="mb-4">{!! $personaje->descripcion_short !!}</div>
            @endif

            {{-- Sección de Detalles Biográficos --}}

            @php
            $hasDetails = $personaje->descripcion || $personaje->salud || $personaje->personalidad ||
            $personaje->deseos || $personaje->miedos || $personaje->magia || $personaje->educacion;
            @endphp

            @if ($hasDetails)
            <h2 class="border-bottom pb-2 mb-3">Detalles Biográficos</h2>

            @if ($personaje->descripcion)
            <h5><i class="fas fa-user mr-2"></i>Descripción física</h5>
            <div class="ml-4 mb-3">{!! $personaje->descripcion !!}</div>
            @endif

            @if ($personaje->salud)
            <h5><i class="fas fa-heartbeat mr-2"></i>Salud</h5>
            <div class="ml-4 mb-3">{!! $personaje->salud !!}</div>
            @endif

            @if ($personaje->personalidad)
            <h5><i class="fas fa-brain mr-2"></i>Personalidad</h5>
            <div class="ml-4 mb-3">{!! $personaje->personalidad !!}</div>
            @endif

            @if ($personaje->deseos)
            <h5><i class="fa-solid fa-star mr-2"></i>Deseos</h5>
            <div class="ml-4 mb-3">{!! $personaje->deseos !!}</div>
            @endif

            @if ($personaje->miedos)
            <h5><i class="fa-solid fa-cloud-bolt mr-2"></i>Miedos</h5>
            <div class="ml-4 mb-3">{!! $personaje->miedos !!}</div>
            @endif

            @if ($personaje->magia)
            <h5><i class="fa-solid fa-wand-magic-sparkles mr-2"></i>Habilidades mágicas</h5>
            <div class="ml-4 mb-3">{!! $personaje->magia !!}</div>
            @endif

            @if ($personaje->educacion)
            <h5><i class="fa-solid fa-graduation-cap mr-2"></i>Educación</h5>
            <div class="ml-4 mb-3">{!! $personaje->educacion !!}</div>
            @endif
            @endif

            @if ($personaje->historia)
            <h2 class="border-bottom pb-2 mt-4 mb-3">Historia</h2>
            <div class="ml-2">{!! $personaje->historia !!}</div>
            @endif

        
            {{-- Sección de Aspectos Sociales y Otros --}}
            @php
            $hasSocials = $personaje->religion || $personaje->familia || $personaje->politica;
            @endphp

            @if ($hasSocials)
            <h2 class="border-bottom pb-2 mb-3">Aspectos sociales y culturales</h2>
            
            @if ($personaje->religion)
            <h5><i class="fa-solid fa-monument mr-2"></i>Religión</h5>
            <div class="ml-4 mb-3">{!! $personaje->religion !!}</div>
            @endif

            @if ($personaje->familia)
            <h5><i class="fa-solid fa-children mr-2"></i>Familia</h5>
            <div class="ml-4 mb-3">{!! $personaje->familia !!}</div>
            @endif

            @if ($personaje->politica)
            <h5><i class="fa-solid fa-scale-balanced mr-2"></i>Política</h5>
            <div class="ml-4 mb-3">{!! $personaje->politica !!}</div>
            @endif
            @endif

             @if ($personaje->otros)
            <h2 class="border-bottom pb-2 mt-4 mb-3">Otros aspectos</h2>
            <div class="ml-2">{!! $personaje->otros !!}</div>
            @endif
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card card-dark">
          <div class="card-header">
            <h3 class="card-title">Ficha de personaje</h3>
          </div>
          <div class="card-body text-center">
            <img alt="Retrato de {{ $personaje->nombre }}"
              class="img-thumbnail shadow-sm mb-3"
              src="{{ asset('storage/retratos/' . ($personaje->retrato ?? 'default.png')) }}"
              style="max-height: 400px; width: 100%; object-fit: cover;">

            <div class="text-left mt-3">
              <strong><i class="fas fa-dna mr-1"></i> Especie:</strong>
              <p class="text-muted">
                <a href="{{ route('especie.show', $personaje->id_foranea_especie) }}">{{ $especie }}</a>
              </p>

              <strong><i class="fas fa-venus-mars mr-1"></i> Sexo:</strong>
              <p class="text-muted">{{ $personaje->sexo }}</p>

              @if($personaje->lugar_nacimiento)
              <strong><i class="fas fa-map-marker-alt mr-1"></i> Lugar de nacimiento:</strong>
              <p class="text-muted">{{ $personaje->lugar_nacimiento }}</p>
              @endif

              @if ($personaje->nacimiento)
              <strong><i class="fa-solid fa-baby mr-1"></i>Fecha de nacimiento</strong>
              <p class="ml-2 mr-2">{{$nacimiento}}</p>
              @endif

              @if ($personaje->fallecimiento)
              <strong><i class="fa-solid fa-skull-crossbones mr-1"></i>Fecha de fallecimiento</strong>
              <p class="ml-2 mr-2">{{$fallecimiento}}</p>
              @endif

              @if($personaje->causa_fallecimiento)
              <div class="alert alert-secondary py-2 mt-2">
                <strong><i class="fas fa-cross mr-1"></i> Causa de fallecimiento:</strong><br>
                <p>{{ $personaje->causa_fallecimiento}}</p>
              </div>
              @endif

              <strong><i class="fas fa-birthday-cake mr-1"></i> Edad:</strong>
              <p class="text-muted">{{ $edad }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection