@extends('layouts.index')


@section('title')
<title id="title">{{$articulo->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  {{-- Ajuste dinámico de la ruta de volver según el tipo --}}
  <a role="button" title="Volver" href="{{ $articulo->tipo === 'Relato' ? route('relatos.index') : route('articulos.index') }}" class="nuevo btn btn-dark">
    <i class="fas fa-arrow-left mr-1"></i>Volver
  </a>
</li>
<li class="nav-item ml-2">
  {{-- Ajuste dinámico de la ruta de editar según el tipo --}}
  <a role="button" title="Editar" href="{{ $articulo->tipo === 'Relato' ? route('relatos.edit', $articulo->id) : route('articulos.edit', $articulo->id) }}" class="btn ml-1 btn-dark">
    <i class="fas fa-pencil-alt mr-2"></i>Editar
  </a>
</li>
@endsection

@section('content')

<article class="content article-container">
  <div class="row">
    {{-- Columna Principal: contenido del artículo --}}
    <div class="{{ $articulo->tipo === 'Relato' ? 'col-lg-9' : 'col-lg-10 offset-lg-1' }}">
      <div class="card main-card card-dark card-outline">
        <div class="card-header bg-white border-bottom-0 pt-4">
          <div class="mailbox-read-info">
            <h2 class="article-title text-center">{{ $articulo->nombre }}</h2>
            
          </div>
          <!-- /.mailbox-read-info -->
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="mailbox-read-message" style="line-height: 1.8; font-size: 1.1rem;">
            {!! clean($articulo->contenido) !!}
          </div>
          <!-- /.mailbox-read-message -->
        </div>
        <div class="card-footer">
          <h6><span class="mailbox-read-time float-right"><i class="far fa-calendar-alt mr-1"></i>Última edición: {{ $articulo->updated_at->format('d/m/Y') }}</span></h6>
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
     {{-- Columna lateral: Personajes (solo si es relato) --}}
      @if($articulo->tipo === 'Relato')
    <div class="col-md-3">
      <div class="card card-dark card-outline shadow-sm sidebar-card">
        <div class="card-header">
          <h3 class="card-title font-weight-bold">Personajes relevantes</h3>
        </div>
        <div class="card-body p-0">
          <ul class="nav nav-pills flex-column">
            @foreach($articulo->personajes_relevantes as $personaje)
            <li class="nav-item">
              <a href="{{route('personaje.show', $personaje->id)}}" class="nav-link personaje-link text-dark">
                <img class="retrato-mini" src="{{ asset("storage/retratos/" . ($personaje->retrato ?? 'default.png')) }}" alt="Retrato de {{ $personaje->nombre }}">
                 {{ $personaje->nombre }}
              </a>
            </li>
          @endforeach
          </ul>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
      @endif
  </div>
</article>
@endsection