@extends('layouts.index')

@section('title')
<title id="title">Configuración</title>
@endsection

@section('navbar-buttons')
@endsection

@section('content')
<div class="row">
  <h1>Configuración</h1>
</div>
<hr>

<!-- Modal -->
<div class="modal fade" id="editar_nombre" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editarNombreLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="card card-dark">
        <div class="card-header">
          <h5 class="card-title" id="editarNombreLabel">Editar</h5>
          <button data-dismiss="modal" aria-label="close" class="close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-editar-nombre" class="col-md-auto" action="{{route('config.update')}}" method="POST">
          <div class="card-body">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_editar" id="id_editar">
            <input type="hidden" name="tipo_editar" id="tipo_editar">
            <div class="row">
              <div class="col">
                <label for="nombre_editar" class="form-label">Nombre</label>
                <input type="text" name="nombre_editar" class="form-control" id="nombre_editar" required>
                <div class="invalid-feedback">
                  Nombre no puede estar vacío.
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="button" id="cancelar-editar-button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
            <button type="submit" id="submit-editar-button" class="btn btn-success">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="col-md-12">
  <!--<div class="row">
    <button id="back_up" class="btn btn-primary backup_button">Copia de seguridad</button>
  </div>-->
  <!--<a href="{{route('galeria.limpiar_imagenes')}}" class="btn btn-dark">Limpiar imágenes</a>-->

  <div class="row">
    <div class="col-4">
      <form id="form-edit-nombre_mundo" class="form-inline" action="{{route('config.update_nombre_mundo')}}" method="POST">
        @csrf
        <div class="form-group mb-2">
          <label for="nombre_mundo" class="sr-only">Nombre del mundo</label>
          <input type="text" readonly class="form-control-plaintext" id="nombre_mundo" value="Nombre del mundo">
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <label for="nuevo_nombre_mundo" class="sr-only">Nombre del mundo</label>
          <input type="text" value="{{$Nombre_mundo}}" name="nuevo_nombre_mundo" class="form-control" id="nuevo_nombre_mundo" placeholder="Ej: Córdoba">
          @error('nuevo_nombre_mundo')
          <small style="color: red">{{$message}}</small>
          @enderror
          <button type="submit" class="btn btn-primary">Cambiar</button>
        </div>
        <input type="hidden" name="id" id="id" value="Nombre_mundo">
      </form>

    </div>
    <div class="col-8">
      <form id="form-edit-fecha_mundo" class="form-inline" action="{{route('config.update_fecha_mundo')}}" method="POST">
        @csrf
        <div class="form-group mb-2">
          <label for="fecha_actual" class="sr-only">Fecha actual en el mundo</label>
          <input type="text" readonly class="form-control-plaintext" id="fecha_actual" value="Fecha actual en el mundo">
        </div>
        <div class="form-group mx-sm-3 mb-2">
          <input type="text" id="dia" name="dia" class="form-control col-2" placeholder="Día" value="{{$fecha->dia}}">
          @error('dia')
          <small style="color: red">{{$message}}</small>
          @enderror
          <select class="form-control col-4" id="mes" name="mes">
            <option selected disabled value="">Mes</option>
            <option value="0" @selected($fecha->mes == 0)>Semana de año nuevo</option>
            <option value="1" @selected($fecha->mes == 1)>Enero</option>
            <option value="2" @selected($fecha->mes == 2)>Febrero</option>
            <option value="3" @selected($fecha->mes == 3)>Marzo</option>
            <option value="4" @selected($fecha->mes == 4)>Abril</option>
            <option value="5" @selected($fecha->mes == 5)>Mayo</option>
            <option value="6" @selected($fecha->mes == 6)>Junio</option>
            <option value="7" @selected($fecha->mes == 7)>Julio</option>
            <option value="8" @selected($fecha->mes == 8)>Agosto</option>
            <option value="9" @selected($fecha->mes == 9)>Septiembre</option>
            <option value="10" @selected($fecha->mes == 10)>Octubre</option>
            <option value="11" @selected($fecha->mes == 11)>Noviembre</option>
            <option value="12" @selected($fecha->mes == 12)>Diciembre</option>
          </select>
          @error('mes')
          <small style="color: red">{{$message}}</small>
          @enderror
          <input type="text" id="anno" name="anno" class="form-control col-2" placeholder="Año" value="{{$fecha->anno}}">
          @error('anno')
          <small style="color: red">{{$message}}</small>
          @enderror
          <button type="submit" class="btn btn-primary">Cambiar</button>
        </div>
      </form>
    </div>
  </div>
  <div class="row">
    <x-config-table :items="$tipos_asentamiento" title="Tipos de asentamientos" :route="'config.store_tipo_asentamiento'" :name="'asentamiento'" :placeholder="'Ej: Pueblo, ciudad...'" />
    <x-config-table :items="$tipos_conflicto" title="Tipos de conflictos" :route="'config.store_tipo_conflicto'" :name="'conflicto'" :placeholder="'Ej: Batalla, intriga...'" />
    <x-config-table :items="$tipos_construccion" title="Tipos de construcciones" :route="'config.store_tipo_construccion'" :name="'construccion'" :placeholder="'Ej: Casa, castillo...'" />
  </div>
  <div class="row">
    <x-config-table :items="$tipos_lugar" title="Tipos de lugares" :route="'config.store_tipo_lugar'" :name="'lugar'" :placeholder="'Ej: Bosque, desierto...'" />
    <x-config-table :items="$tipos_organizaciones" title="Tipos de organizaciones" :route="'config.store_tipo_organizacion'" :name="'organizacion'" :placeholder="'Ej: Imperio, ejército...'" />
  </div>

</div>
<!-- /.col -->
<x-modal-delete 
    id="confirmar_eliminacion" 
    :route="route('config.destroy')" 
    title="Confirmar eliminación" 
    message="¿Estás seguro de que deseas eliminar este registro de configuración?" 
/>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/config.js')}}"></script>
@endsection