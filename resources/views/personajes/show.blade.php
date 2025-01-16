@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">{{$vista->Nombre}}</title>
@endsection

@section('navbar-buttons')
<a href="{{route('personajes.index')}}" class="btn btn-dark">Volver</a>
<a href="{{route('personaje.edit', ['id'=> $vista->id] )}}" class="btn btn-dark ml-2">Editar</a>
@endsection

@section('content')
<h1>{{$vista->Nombre}}</h1>

<section class="content">
  <div class="container margin-top-20 mt-5 page">
    <div class="row article-content">
      <div class="col-md contentApp" id="content-left">
        <div class="col text-center">
          <h1>{{$vista->Nombre}} {{$vista->nombreFamilia}} {{$vista->Apellidos}}</h1>
        </div>
        <h3 class="mt-3">Descripción breve</h3>
        <p class="ml-2 mr-2">{!!$vista->DescripcionShort!!}</p>

        <h2 class="mb-3">Descripción</h2>
        @if (isset($vista->Descripcion))
        <h3>Descripción física</h3>
        <p class="ml-2 mr-2">{!!$vista->Descripcion!!}</p>
        @endif

        @if (isset($vista->salud))
        <h3>Enfermedades, heridas o problemas de salud</h3>
        <p class="ml-2 mr-2">{!!$vista->salud!!}</p>
        @endif

        @if (isset($vista->Personalidad))
        <h3>Personalidad</h3>
        <p class="ml-2 mr-2">{!!$vista->Personalidad!!}</p>
        @endif
        @if (isset($vista->Deseos))
        <h3>Deseos</h3>
        <p class="ml-2 mr-2">{!!$vista->Deseos!!}</p>
        @endif
        @if (isset($vista->Miedos))
        <h3>Miedos</h3>
        <p class="ml-2 mr-2">{!!$vista->Miedos!!}</p>
        @endif
        @if (isset($vista->Magia))
        <h3>Habilidades mágicas</h3>
        <p class="ml-2 mr-2">{!!$vista->Magia!!}</br></p>
        @endif
        @if (isset($vista->educacion))
        <h3>Educación</h3>
        <p class="ml-2 mr-2">{!!$vista->educacion!!}</br></p>
        @endif
        @if (isset($vista->Historia))
        <h2>Historia</h2>
        <p class="ml-2 mr-2">{!!$vista->Historia!!}</p>
        @endif
        <h2 class="mb-3">Aspectos sociales</h2>
        @if (isset($vista->Religion))
        <h3>Religión</h3>
        <p class="ml-2 mr-2">{!!$vista->Religion!!}</p>
        @endif
        @if (isset($vista->Familia))
        <h3>Familia</h3>
        <p class="ml-2 mr-2">{!!$vista->Familia!!}</p>
        @endif
        @if (isset($vista->Politica))
        <h3>Política</h3>
        <p class="ml-2 mr-2">{!!$vista->Politica!!}</p>
        @endif
        @if (isset($vista->otros))
        <h2>Otros</h2>
        <p class="ml-2 mr-2">{!!$vista->otros!!}</p>
        @endif
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body contentApp" id="content-right">
            <h3>Retrato</h3>
            <div class="row">
              <img alt="retrato" id="retrato" class="img-fluid" src="{{asset("storage/retratos/{$vista->Retrato}")}}" width="300" height="300">
            </div>
            <h3>Especie</h3>
            <p class="ml-1 mr-2"><a href="{{route('especie.show', [$vista->id_foranea_especie] )}}">{{$especie}}</a></p>

            <h3>Sexo</h3>
            <p class="ml-2 mr-2">{{$vista->Sexo}}</p>

            
            @if (isset($vista->lugarNacimiento))
            <h3>Lugar de nacimiento</h3>
            <p class="ml-2 mr-2">{{$vista->lugarNacimiento}}</p>
            @endif

            @if ($vista->nacimiento!=0)
            <h3>Fecha de nacimiento</h3>
            <p class="ml-2 mr-2">{{$nacimiento}}</p>
            @endif

            @if ($vista->fallecimiento!=0)
            <h3>Fecha de fallecimiento</h3>
            <p class="ml-2 mr-2">{{$fallecimiento}}</p>
            @endif

            <h3>Edad</h3>
            <p class="ml-2 mr-2">{{$edad}}</p>

            @if (isset($vista->causa_fallecimiento))
            <h3>Causa de fallecimiento</h3>
            <p class="ml-2 mr-2">{{$vista->causa_fallecimiento}}</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection