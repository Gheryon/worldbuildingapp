@extends('layouts.index')

@section('title')
<title id="title">Nuevo lugar</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('lugares.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="container-fluid">
  {{-- Encabezado --}}
  <div class="row mb-4">
    <div class="col-12 text-center">
      <h1 class="display-4 text-primary-custom font-weight-bold">
        <i class="nav-icon fa-solid fa-mountain-sun mr-2"></i>Nuevo lugar
      </h1>
    </div>
  </div>

  <form id="form-create-lugar" data-prevent-loss="true" class="needs-validation" action="{{route('lugar.store')}}" method="post" enctype="multipart/form-data">
    @csrf

    {{-- Botón de Acción Superior --}}
    <div class="row mb-3">
      <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success btn-lg shadow-sm">
          <i class="fas fa-plus-circle mr-2"></i> Crear Lugar
        </button>
      </div>
    </div>

    {{-- Bloque de información técnica --}}
    <div class="card card-outline card-dark shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-id-card mr-1"></i> Información técnica</h3>
      </div>
      <div class="card-body bg-light">
        <div class="row">
          {{-- Columna de Identidad y Clasificación (8/12) --}}
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6 mb-3">
                <x-text-input name="nombre" label="Nombre del lugar" placeholder="Ej: Pico del Destino" :value="old('nombre')" required />
              </div>
              <div class="col-md-6 mb-3">
                <x-text-input name="otros_nombres" label="Otros nombres / Alias" placeholder="Ej: La montaña de fuego" :value="old('otros_nombres')" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="select_tipo" class="form-label font-weight-bold">Tipo de lugar</label>
                <select class="form-control select2bs4" name="select_tipo" id="select_tipo" required>
                  <option selected disabled value="">Elegir tipo...</option>
                  @foreach($tipos as $tipo)
                  <option value="{{$tipo->id}}" {{ old('select_tipo') == $tipo->id ? 'selected' : '' }}>{{$tipo->nombre}}</option>
                  @endforeach
                </select>
            @error('select_tipo')
            <small style="color: red">{{$message}}</small>
            @enderror
              </div>
              <div class="col-md-4 mb-3">
                <label for="nivel_peligro" class="form-label font-weight-bold">Nivel de peligro</label>
                <select class="form-control select2bs4" name="nivel_peligro" id="nivel_peligro">
                  <option selected value="">Elegir nivel...</option>
                  @foreach(['Ninguno', 'Bajo', 'Moderado', 'Alto', 'Mortal', 'Desconocido'] as $nivel)
                  <option value="{{ $nivel }}" {{ old('nivel_peligro') == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label for="tipo_peligro" class="form-label font-weight-bold">Naturaleza del peligro</label>
                <select class="form-control select2bs4" name="tipo_peligro" id="tipo_peligro">
                  <option selected value="">Elegir origen...</option>
                  @foreach(['Mágico', 'Fauna', 'Clima', 'Geológico', 'Político', 'Sobrenatural', 'Ninguno'] as $t_pel)
                  <option value="{{ $t_pel }}" {{ old('tipo_peligro') == $t_pel ? 'selected' : '' }}>{{ $t_pel }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          {{-- Columna de Atributos de Acceso y Privacidad (4/12) --}}
          <div class="col-md-4 border-left">
            <div class="row">
              <div class="col-12 mb-3">
                <label for="dificultad_acceso" class="form-label font-weight-bold">Accesibilidad</label>
                <select class="form-control select2bs4" name="dificultad_acceso" id="dificultad_acceso">
                  <option selected value="">Elegir dificultad...</option>
                  @foreach(['Muy fácil', 'Fácil', 'Moderada', 'Difícil', 'Extrema'] as $dif)
                  <option value="{{ $dif }}" {{ old('dificultad_acceso') == $dif ? 'selected' : '' }}>{{ $dif }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 mb-3">
                <x-text-input name="estacionalidad" label="Estacionalidad" placeholder="Ej: Solo en invierno" :value="old('estacionalidad')" />
              </div>
              <div class="col-12">
                <label class="form-label font-weight-bold">Estado</label>
                <div class="custom-control custom-switch mt-1">
                  <input type="checkbox" class="custom-control-input" id="es_secreto" name="es_secreto" {{ old('es_secreto') ? 'checked' : '' }}>
                  <label class="custom-control-label font-weight-normal" for="es_secreto">Marcar como lugar secreto</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Panel de Pestañas --}}
    <div class="card card-dark card-outline card-tabs mt-4 shadow-sm">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="lugarTab" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tab-desc"><i class="fas fa-align-left mr-1"></i> Descripción</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-entorno"><i class="fas fa-cloud-sun mr-1"></i> Entorno</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-biologia"><i class="fas fa-leaf mr-1"></i> Biología</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-recursos"><i class="fas fa-gem mr-1"></i> Recursos</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-lore"><i class="fas fa-book mr-1"></i> Lore</a></li>
        </ul>
      </div>
      <div class="card-body bg-white">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="tab-desc">
            <x-textarea-input name="descripcion_breve" label="Descripción general" :value="old('descripcion_breve')" />
            <x-textarea-input name="geografia" label="Geografía detallada" :value="old('geografia')" />
          </div>
          <div class="tab-pane fade" id="tab-entorno">
            <div class="row">
              <div class="col-md-6"><x-textarea-input name="ecosistema" label="Ecosistema" :value="old('ecosistema')" /></div>
              <div class="col-md-6"><x-textarea-input name="clima" label="Clima" :value="old('clima')" /></div>
            </div>
            <x-textarea-input name="fenomeno_unico" label="Fenómeno único" :value="old('fenomeno_unico')" />
          </div>
          <div class="tab-pane fade" id="tab-biologia">
            <x-textarea-input name="flora_fauna" label="Flora y Fauna" :value="old('flora_fauna')" />
          </div>
          <div class="tab-pane fade" id="tab-recursos">
            <x-textarea-input name="recursos" label="Recursos generales" :value="old('recursos')" />
          </div>
          <div class="tab-pane fade" id="tab-lore">
            <x-textarea-input name="historia" label="Historia del lugar" class="summernote" rows="12" :value="old('historia')" />
            <div class="row">
              <div class="col-md-6"><x-textarea-input name="rumores" label="Rumores y leyendas" :value="old('rumores')" /></div>
              <div class="col-md-6"><x-textarea-input name="otros" label="Notas adicionales" :value="old('otros')" /></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
@endsection