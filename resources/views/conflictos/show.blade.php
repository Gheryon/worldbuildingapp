@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">{{$vista->nombre}}</title>
@endsection

@section('navbar-buttons')
<a href="{{route('conflictos.index')}}" class="btn btn-dark">Volver</a>
<a href="{{route('conflicto.edit', ['id'=> $vista->id] )}}" class="btn btn-dark ml-2">Editar</a>
@endsection

@section('content')

<section class="content">
  <div class="container margin-top-20 mt-5 page">
    <div class="row article-content">
      <div class="col-md contentApp" id="content-left">
        <div class="col text-center">
          <h1>{{$vista->nombre}} </h1>
        </div>

        @if (isset($vista->descripcion))
        <h3 class="mt-3">Descripción</h3>
        <p class="ml-2 mr-2">{!!$vista->descripcion!!}</p>
        @endif

        @if (isset($vista->preludio))
        <h3>Preludio</h3>
        <p class="ml-2 mr-2">{!!$vista->preludio!!}</p>
        @endif

        @if (isset($vista->desarrollo))
        <h3>Desarrollo</h3>
        <p class="ml-2 mr-2">{!!$vista->desarrollo!!}</p>
        @endif

        @if (isset($vista->resultado))
        <h3>Resultado</h3>
        <p class="ml-2 mr-2">{!!$vista->resultado!!}</p>
        @endif

        @if (isset($vista->consecuencias))
        <h3>Consecuencias</h3>
        <p class="ml-2 mr-2">{!!$vista->consecuencias!!}</p>
        @endif

        @if (isset($vista->otros))
        <h3>Otros</h3>
        <p class="ml-2 mr-2">{!!$vista->otros!!}</p>
        @endif

      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body contentApp" id="content-right">
            <h3 class="mt-2">Tipo</h3>
            <p class="ml-1 mr-2">{{$tipo}}</p>

            @if (isset($padre->id))
            <h3 class="mt-2">Parte del conflicto:</h3>
            <p class="ml-1 mr-2"><a href="{{route('conflicto.show', [$padre->id] )}}">{{$padre->nombre}}</a></p>
            @endif

            @if (isset($vista->tipo_localizacion))
            <h3 class="mt-2">Tipo de localización</h3>
            <p class="ml-2 mr-2">{{$vista->tipo_localizacion}}</p>
            @endif

            @if ($vista->fecha_inicio!=0)
            <h3 class="mt-2">Comienzo</h3>
            <p class="ml-2 mr-2">{{$inicio}}</p>
            @endif

            @if ($vista->fecha_fin!=0)
            <h3 class="mt-2">Finalización</h3>
            <p class="ml-2 mr-2">{{$fin}}</p>
            @endif

            @if (isset($atacantes))
            @if (filled($atacantes))
            <h3 class="mt-2">Atacantes</h3>
            @foreach($atacantes as $atacante)
            <p class="ml-1 mr-2"><a href="{{route('organizacion.show', [$atacante->id_organizacion] )}}">{{$atacante->nombre}}</a></p>
            @endforeach
            @endif
            @endif

            @if (isset($defensores))
            @if (filled($defensores))
            <h3 class="mt-2">Defensores</h3>
            @foreach($defensores as $defensor)
            <p class="ml-1 mr-2"><a href="{{route('organizacion.show', [$defensor->id_organizacion] )}}">{{$defensor->nombre}}</a></p>
            @endforeach
            @endif
            @endif

            @if (isset($atacantesp))
            @if (filled($atacantesp))
            <h3 class="mt-2">Personajes atacantes relevantes</h3>
            @foreach($atacantesp as $atacante)
            <p class="ml-1 mr-2"><a href="{{route('personaje.show', [$atacante->id] )}}">{{$atacante->nombre}}</a></p>
            @endforeach
            @endif
            @endif

            @if (isset($defensoresp))
            @if (filled($defensoresp))
            <h3 class="mt-2">Personajes defensores relevantes</h3>
            @foreach($defensoresp as $defensor)
            <p class="ml-1 mr-2"><a href="{{route('personaje.show', [$defensor->id] )}}">{{$defensor->nombre}}</a></p>
            @endforeach
            @endif
            @endif

            @if (isset($relacionados))
            @if (filled($relacionados))
            <h3 class="mt-2">Relacionados</h3>
            @foreach($relacionados as $r)
            <p class="ml-1 mr-2"><a href="{{route('conflicto.show', [$r->id] )}}">{{$r->nombre}}</a></p>
            @endforeach
            @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection