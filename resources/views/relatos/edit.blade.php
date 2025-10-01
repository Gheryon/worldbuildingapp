@extends('layouts.index')
@extends('layouts.navbar')
@extends('layouts.menu')

@section('title')
<title id="title">Editar {{$relato->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('relatos')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <h1 id="title-h1">Editar artículo {{$relato->nombre}}</h1>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-edit"  action="{{route('relatos.update', $relato->id_articulo )}}" method="post">
    @csrf
    @method('PUT')
    <div class="row mb-3 justify-content-center">
      <button type="submit" class="btn btn-success ml-1" id="guardar-button">Guardar</button>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline">
            <div class="card-header">
              <div class="row mb-2">
                <div class="col">
                  <label for="nombre" class="form-label">Nombre</label>
                  <input type="text" value="{{$relato->nombre}}" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required>
                  @error('nombre')
                  <small style="color: red">{{$message}}</small>
                  @enderror
                </div>
                <div class="col-md-4">
                  <label for="personajes" class="form-label">Personajes relevantes</label>
                  <select class="form-select form-control" multiple="multiple" data-placeholder="Personajes" name="personajes[]" id="personajes" style="width: 100%;">
                    <option selected disabled value="">Elegir</option>
                    @if (Arr::has($personajes, 'error.error'))
                    <option disabled value="">Se produjo un error en la base de datos</option>
                    @else
                      @if($personajes->isEmpty())
                      <option disabled value="">No hay personajes guardados.</option>
                      @else
                      @foreach($personajes as $persona)
                    <option value="{{ $persona->id }}" {{ in_array($persona->id, $personajes_r->pluck('personaje')->toArray()) ? 'selected' : '' }}>{{ $persona->Nombre }}</option>
                      @endforeach
                      @endif
                    @endif
                  </select>
                  @error('personajes')
                  <small style="color: red">{{$message}}</small>
                  @enderror
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <label for="contenido" class="form-label">Contenido</label>
              <textarea class="form-control summernote" id="contenido" name="contenido" rows="8" aria-label="With textarea">{!!$relato->contenido!!}</textarea>
                @error('contenido')
                <small style="color: red">{{$message}}</small>
                @enderror
            </div>
            <div class="card-footer">
            </div>
          </div>
        </div><!-- /.col-->
      </div><!-- ./row -->
    </div> <!-- /container -->
  </form>
</section>
<!-- /.content -->

@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
<script>
  $(function() {
    $('#tipo').val('{{$relato->tipo}}');
  });
</script>
@endsection