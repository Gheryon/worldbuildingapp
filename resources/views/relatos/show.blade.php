@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">{{$relato->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a role="button" title="Volver" href="{{route('relatos')}}" class="nuevo btn btn-dark">Volver</a>
</li>
<li class="nav-item ml-2">
  <a href="{{route('relatos.edit',$relato->id_articulo)}}" role="button" title="Editar" class="btn ml-1 btn-dark"><i class="fas fa-pencil-alt mr-2"></i>Editar</a>
</li>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-1">
      <div class="col-sm-12">
        <h1 id="content-title-h1" class="fw-bolder text-center contentApp"></h1>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
  <div class="container margin-top-20 mt-5 page">
    <div class="row article-content">
      <div class="col">
        <div class="row personaje" id="contenido">
          {!!$relato->contenido!!}
        </div>
      </div>

      @if (isset($personajes))
      @if (filled($personajes))
      <div class="col-3">
        <div class="card">
          <div class="card-body contentApp" id="content-right">
            <h3 class="mt-2">Personajes relacionados</h3>
            @foreach($personajes as $personaje)
            <p class="ml-1 mr-2"><a href="{{route('personaje.show', [$personaje->id] )}}">{{$personaje->nombre}}</a></p>
            @endforeach
          </div>
        </div>
      </div>
      @endif
      @endif
    </div>
    <div class="row justify-content-md-center">
      <div class="col-2">
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection

@section('specific-scripts')

@endsection