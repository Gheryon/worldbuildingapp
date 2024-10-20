@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">{{$vista->nombre}}</title>
@endsection

@section('navbar-buttons')
<a href="{{route('especies.index')}}" class="btn btn-dark">Volver</a>
<a href="{{route('especie.edit', ['id'=> $vista->id] )}}" class="btn btn-dark ml-2">Editar</a>
@endsection

@section('content')

<section class="content">
  <div class="container margin-top-20 mt-5 page">
    <div class="row article-content">
      <div class="col-md contentApp" id="content-left">
        <div class="col text-center">
          <h1>{{$vista->nombre}} </h1>
        </div>
        
        @if (isset($vista->anatomia))
          <h3 class="mt-3">Anatomía</h3>
          <p class="ml-2 mr-2">{!!$vista->anatomia!!}</p>
        @endif

        @if (isset($vista->alimentacion))
          <h3>Alimentación</h3>
          <p class="ml-2 mr-2">{!!$vista->alimentacion!!}</p>
        @endif

        @if (isset($vista->reproduccion))
          <h3>Reproducción y crecimiento</h3>
          <p class="ml-2 mr-2">{!!$vista->reproduccion!!}</p>
        @endif
        @if (isset($vista->distribucion))
          <h3>Distribución y hábitats</h3>
          <p class="ml-2 mr-2">{!!$vista->distribucion!!}</p>
        @endif
        @if (isset($vista->hablidades))
          <h3>Habilidades</h3>
          <p class="ml-2 mr-2">{!!$vista->habilidades!!}</p>
        @endif
        @if (isset($vista->domesticacion))
          <h3>Domesticación</h3>
          <p class="ml-2 mr-2">{!!$vista->domesticacion!!}</p>
        @endif
        @if (isset($vista->explotacion))
          <h3>Explotación</h3>
          <p class="ml-2 mr-2">{!!$vista->explotacion!!}</p>
        @endif

        @if (isset($vista->otros))
        <h3>Otros</h3>
        <p class="ml-2 mr-2">{!!$vista->otros!!}</p>
        @endif
        
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body contentApp" id="content-right">
            @if (isset($vista->edad))
              <h3 class="mt-2">Esperanza de vida:</h3>
              <p class="ml-1 mr-2">{{$vista->edad}}</p>
            @endif
            @if (isset($vista->altura))
            <h3 class="mt-2">Altura</h3>
            <p class="ml-2 mr-2">{{$vista->altura}}</p>
            @endif
            @if (isset($vista->peso))
            <h3 class="mt-2">Peso</h3>
            <p class="ml-2 mr-2">{{$vista->peso}}</p>
            @endif
            @if (isset($vista->longitud))
            <h3 class="mt-2">Longitud</h3>
            <p class="ml-2 mr-2">{{$vista->longitud}}</p>
            @endif
            <h3 class="mt-2">Estatus</h3>
            <p class="ml-1 mr-2">{{$vista->estatus}}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection