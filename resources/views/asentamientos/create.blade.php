@extends('layouts.index')

@section('title')
<title id="title">Nuevo asentamiento</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('asentamientos.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="container-fluid">
  {{-- Encabezado --}}
  <div class="row mb-4">
    <div class="col-12 text-center">
      <h1 class="display-4 text-primary-custom font-weight-bold">
        <i class="nav-icon fa-solid fa-house mr-2"></i>Nuevo asentamiento
      </h1>
    </div>
  </div>

  <form id="form-create-asentamiento" data-prevent-loss="true" class="needs-validation" action="{{route('asentamiento.store')}}" method="post" enctype="multipart/form-data">
    @csrf

    {{-- Botón de Acción Superior --}}
    <div class="row mb-3">
      <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success btn-lg shadow-sm">
          <i class="fas fa-plus-circle mr-2"></i> Crear Asentamiento
        </button>
      </div>
    </div>

    {{-- Bloque de Datos Primarios --}}
    <div class="card card-outline card-dark shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-id-card mr-1"></i> Información técnica</h3>
      </div>
      <div class="card-body bg-light">
        <div class="row">
          {{-- Columna de Identidad --}}
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6 mb-3">
                <x-text-input name="nombre" label="Nombre del asentamiento" placeholder="Ej: Minas Tirith" :value="old('nombre')" required />
              </div>
              <div class="col-md-6 mb-3">
                <x-text-input name="gentilicio" label="Gentilicio" placeholder="Ej: Gondoriano" :value="old('gentilicio')" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="select_tipo" class="form-label font-weight-bold">Tipo</label>
                <select class="form-control select2bs4" name="select_tipo" id="select_tipo" required>
                  <option selected disabled value="">Elegir tipo...</option>
                  @foreach($tipos_asentamientos as $tipo)
                  <option value="{{$tipo->id}}" {{ old('select_tipo') == $tipo->id ? 'selected' : '' }}>{{$tipo->nombre}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label for="estatus" class="form-label font-weight-bold">Estatus actual</label>
                <select class="form-control select2bs4" name="estatus" id="estatus" required>
                  <option selected value="">Elegir...</option>
                  @foreach(['Abandonado', 'En ruinas', 'Habitado', 'Secreto', 'Olvidado'] as $est)
                  <option value="{{ $est }}" {{ old('estatus') == $est ? 'selected' : '' }}>{{ $est }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label for="poblacion" class="form-label font-weight-bold">Población estimada</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-users"></i></span></div>
                  <input type="number" name="poblacion" class="form-control" id="poblacion" placeholder="Ej: 5000" value="{{ old('poblacion') }}">
                </div>
              </div>
            </div>
          </div>

          {{-- Columna de control político --}}
          <div class="col-md-4 border-left">
            <div class="mb-3">
              <label for="select_owner" class="form-label font-weight-bold">Controlado por:</label>
              <select class="form-control select2bs4" name="select_owner" id="select_owner">
                <option value="" selected>Independiente / Ninguno</option>
                @foreach($paises as $id => $nombre)
                <option value="{{$id}}" {{ old('select_owner') == $id ? 'selected' : '' }}>{{$nombre}}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="select_gobernante" class="form-label font-weight-bold">Gobernante local:</label>
              <select class="form-control select2bs4" name="select_gobernante" id="select_gobernante">
                <option value="" selected>Sin asignar</option>
                @foreach($personajes as $id => $nombre)
                <option value="{{$id}}" {{ old('select_gobernante') == $id ? 'selected' : '' }}>{{$nombre}}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>

        <hr class="my-4">

        {{-- Fila de Fechas --}}
        <div class="row">
          <div class="col-md-6">
            <x-date-input-group name="fundacion" label="Fecha de fundación" :dia="old('dia_fundacion')" :mes="old('mes_fundacion')" :anno="old('anno_fundacion')" />
          </div>
          <div class="col-md-6">
            <x-date-input-group name="disolucion" label="Fecha de disolución" :dia="old('dia_disolucion')" :mes="old('mes_disolucion')" :anno="old('anno_disolucion')" />
          </div>
        </div>
      </div>
    </div>

    {{-- Panel de Pestañas --}}
    <div class="card card-dark card-outline card-tabs mt-4 shadow-sm">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="asentamientoTab" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tab-descripcion"><i class="fas fa-align-left mr-1"></i> Descripción</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-social"><i class="fas fa-users mr-1"></i> Sociedad</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-politica"><i class="fas fa-gavel mr-1"></i> Política</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-economia"><i class="fas fa-coins mr-1"></i> Economía</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-historia"><i class="fas fa-book mr-1"></i> Historia</a></li>
        </ul>
      </div>
      <div class="card-body bg-white">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-descripcion">
            <div class="row">
              <div class="col-12 mb-3"><x-textarea-input name="descripcion" label="Descripción general" :value="old('descripcion')" /></div>
              <div class="col-md-6"><x-textarea-input name="geografia" label="Geografía" :value="old('geografia')" /></div>
              <div class="col-md-6"><x-textarea-input name="clima" label="Clima" :value="old('clima')" /></div>
              <div class="col-12"><x-textarea-input name="ubicacion_detalles" label="Detalles especiales o secretos" :value="old('ubicacion_detalles')" /></div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab-social">
            <x-textarea-input name="demografia" label="Composición demográfica" :value="old('demografia')" />
            <x-textarea-input name="cultura" label="Tradiciones, costumbres y cultura" :value="old('cultura')" />
            <x-textarea-input name="arquitectura" label="Arquitectura y monumentos" :value="old('arquitectura')" />
            <x-textarea-input name="infraestructura" label="Servicios e infraestructura" :value="old('infraestructura')" />
          </div>
          <div class="tab-pane fade" id="tab-politica">
            <x-textarea-input name="gobierno" label="Sistema de gobierno" :value="old('gobierno')" />
            <x-textarea-input name="defensas" label="Murallas y fortificaciones" :value="old('defensas')" />
            <x-textarea-input name="ejercito" label="Guarnición y fuerzas militares" :value="old('ejercito')" />
          </div>
          <div class="tab-pane fade" id="tab-economia">
            <div class="row">
              <div class="col-md-6"><x-text-input name="recurso_principal" label="Recurso principal" placeholder="Ej: Plata, carbón, etc." :value="old('recurso_principal')" /></div>
              <div class="col-md-6"><x-text-input name="nivel_riqueza" label="Nivel de riqueza" placeholder="Ej: Bajo, medio, alto, opulento, etc." :value="old('nivel_riqueza')" /></div>
            </div>
            <x-textarea-input name="economia" label="Economía, industria y comercio" :value="old('economia')" />
            <x-textarea-input name="recursos" label="Recursos naturales" :value="old('recursos')" />
          </div>
          <div class="tab-pane fade" id="tab-historia">
            <x-textarea-input name="historia" label="Historia del asentamiento" class="summernote" rows="12" :value="old('historia')" />
            <x-textarea-input name="otros" label="INotas adicionales" :value="old('otros')" />
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

</section>
<!-- /.content -->
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
@endsection