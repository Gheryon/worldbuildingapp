@extends('layouts.index')

@section('title')
<title id="title">Nombres</title>
@endsection

@section('navbar-buttons')
@endsection

@section('content')
<div class="row">
  <h1>Nombres</h1>
</div>
<hr>

<!-- Modal -->
<div class="modal fade" id="editar_nombres" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="Editar nombre" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="card card-dark">
        <div class="card-header">
          <h5 class="card-title" id="nuevoEventoLabel">Editar</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="card-body">
          <form id="form-editar-nombre" class="col-md-auto" action="{{route('nombres.update')}}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_editar" id="id_editar">
            <div class="row">
              <div class="col">
                <label for="nombres_editar" class="form-label">Nombres</label>
                <textarea name="nombres_editar" class="form-control" id="nombres_editar" rows="8" aria-label="With textarea"></textarea>
                <div class="invalid-feedback">
                  Nombre no puede estar vacío.
                </div>
              </div>
            </div>
        </div>
        <div class="card-footer">
          <button type="button" id="cancelar-editar-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
          <button type="submit" id="submit-editar-button" class="btn btn-success">Guardar</button>
          <button type="button" id="cerrar-editar-button" class="btn btn-primary" data-dismiss="modal" style="display:none">Cerrar</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="col-md-12">
  <div class="row">
    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Hombres</h5>
      </div>
      <div class="card-body">
        @if (!$hombres)
        No hay nombres de hombres disponibles.
        @else
        {{$hombres}}
        @endif
      </div>
      <button id="Hombres" nombre="{{$hombres}}" title="Editar" class="editar-nombres btn btn-sm btn-success" data-toggle="modal" data-target="#editar_nombres">Editar</button>
      <div class="card-footer">
        <label for="form-add-nombre-h" class="form-label">Añadir nuevo</label>
        <form id="form-add-nombre-h" class="row" action="{{route('nombre.store_nombre')}}" method="POST">
          @csrf
          <div class="col">
            <input type="hidden" name="id" id="id" value="Hombres">
            <input type="text" value="{{old('nuevo_nombre')}}" name="nuevo_nombre" class="form-control" id="nuevo_nombre" placeholder="Ej: Gumersindo">
            @error('nuevo_nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-3 align-bottom">
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </form>
      </div>
    </div><!--card -->

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Mujeres</h5>
      </div>
      <div class="card-body">
        @if (!$mujeres)
        No hay nombres de mujeres disponibles.
        @else
        {{$mujeres}}
        @endif
      </div>
      <button id="Mujeres" nombre="{{$mujeres}}" title="Editar" class="editar-nombres btn btn-sm btn-success" data-toggle="modal" data-target="#editar_nombres">Editar</button>

      <div class="card-footer">
        <label for="nuevoTimeline" class="form-label">Añadir nuevo</label>
        <form id="form-add-nombre-m" class="row" action="{{route('nombre.store_nombre')}}" method="POST">
          @csrf
          <div class="col">
            <input type="hidden" name="id" id="id" value="Mujeres">
            <input type="text" value="{{old('nuevo_nombre')}}" name="nuevo_nombre" class="form-control" id="nuevo_nombre" placeholder="Ej: Nicolasa">
            @error('nuevo_nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-3 align-bottom">
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </form>
      </div>
    </div><!--card -->
  </div>

  <div class="row">

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Lugares</h5>
      </div>
      <div class="card-body">
        @if (!$lugares)
        No hay nombres de lugares disponibles.
        @else
        {{$lugares}}
        @endif
      </div>
      <button id="Lugares" nombre="{{$lugares}}" title="Editar" class="editar-nombres btn btn-sm btn-success" data-toggle="modal" data-target="#editar_nombres">Editar</button>

      <div class="card-footer">
        <label for="nuevoTimeline" class="form-label">Añadir nuevo</label>
        <form id="form-add-nombre-lugar" class="row" action="{{route('nombre.store_nombre')}}" method="POST">
          @csrf
          <div class="col">
            <input type="hidden" name="id" id="id" value="Lugares">
            <input type="text" value="{{old('nuevo_nombre')}}" name="nuevo_nombre" class="form-control" id="nuevo_nombre" placeholder="Ej: Córdoba">
            @error('nuevo_nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-3 align-bottom">
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </form>
      </div>
    </div><!--card -->

    <div class="card col ml-1">
      <div class="card-header">
        <h5 class="card-title">Sin decidir</h5>
      </div>
      <div class="card-body">
        @if (!$sindecidir)
        No hay nombres disponibles.
        @else
        {{$sindecidir}}
        @endif
      </div>
      <button id="Sin_decidir" nombre="{{$sindecidir}}" title="Editar" class="editar-nombres btn btn-sm btn-success" data-toggle="modal" data-target="#editar_nombres">Editar</button>

      <div class="card-footer">
        <label for="nuevoTimeline" class="form-label">Añadir nuevo</label>
        <form id="form-add-nombre-sindecidir" class="row" action="{{route('nombre.store_nombre')}}" method="POST">
          @csrf
          <div class="col">
            <input type="hidden" name="id" id="id" value="Sin_decidir">
            <input type="text" value="{{old('nuevo_nombre')}}" name="nuevo_nombre" class="form-control" id="nuevo_nombre" placeholder="Ej: Algo">
            @error('nuevo_nombre')
            <small style="color: red">{{$message}}</small>
            @enderror
          </div>
          <div class="col-3 align-bottom">
            <button type="submit" class="btn btn-primary">Añadir</button>
          </div>
        </form>
      </div>
    </div><!--card -->
  </div>
</div>
<!-- /.col -->

@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
@endsection