@extends('layouts.index')

@section('title')
<title id="title">Nuevo conflicto</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('conflictos.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="container-fluid">
  {{-- Encabezado similar a Construcciones --}}
  <div class="row mb-4">
    <div class="col-12 text-center">
      <h1 class="display-4 text-primary-custom font-weight-bold">
        <i class="nav-icon fa-solid fa-swords mr-2"></i>Nuevo conflicto
      </h1>
    </div>
  </div>

  <form id="form-create-conflicto" data-prevent-loss="true" class="needs-validation" action="{{route('conflicto.store')}}" method="post">
    @csrf

    {{-- Botón de Acción Superior --}}
    <div class="row mb-3">
      <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success btn-lg shadow-sm">
          <i class="fas fa-save mr-2"></i> Guardar conflicto
        </button>
      </div>
    </div>

    {{-- Bloque de Datos Primarios y Ubicación Polimórfica --}}
    <div class="card card-outline card-dark shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Identidad y localización</h3>
      </div>
      <div class="card-body bg-light">
        <div class="row">
          <div class="col-md-7">
            <div class="row">
              <div class="col-md-12 mb-3">
                <x-text-input name="nombre" label="Nombre del conflicto" placeholder="Ej: Guerra de las Tres Coronas" :value="old('nombre')" required />
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="tipo_conflicto_id" class="form-label font-weight-bold">Tipo de conflicto</label>
                <select class="form-control select2bs4" name="tipo_conflicto_id" id="tipo_conflicto_id">
                  <option selected disabled value="">Elegir tipo...</option>
                  @foreach($tipos_conflicto as $tipo)
                  <option value="{{$tipo->id}}" {{ old('tipo_conflicto_id') == $tipo->id ? 'selected' : '' }}>{{$tipo->nombre}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="conflicto_padre_id" class="form-label font-weight-bold">Conflicto padre</label>
                <select class="form-control select2bs4" name="conflicto_padre_id" id="conflicto_padre_id">
                  <option value="" selected>Conflicto independiente</option>
                  @foreach($conflictos as $id => $nombre)
                  <option value="{{$id}}" {{ old('conflicto_padre_id') == $id ? 'selected' : '' }}>{{$nombre}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          {{-- Columna de Ubicación Polimórfica --}}
          <div class="col-md-5 border-left">
            <label class="form-label font-weight-bold"><i class="fas fa-map-marker-alt mr-1"></i> Ubicación principal</label>
            <div class="row">
              <div class="col-md-5 mb-3">
                <select class="form-control" name="ubicacion_principal_type" id="ubicacion_type">
                  <option value="App\Models\Asentamiento" {{ old('ubicacion_principal_type') == 'App\Models\Asentamiento' ? 'selected' : '' }}>Asentamiento</option>
                  <option value="App\Models\Lugar" {{ old('ubicacion_principal_type') == 'App\Models\Lugar' ? 'selected' : '' }}>Lugar geográfico</option>
                </select>
              </div>
              <div class="col-md-7 mb-3">
                <select class="form-control select2bs4" name="ubicacion_principal_id" id="ubicacion_id">
                  <option value="">Seleccionar destino...</option>
                  {{-- Se llena vía JS según el tipo --}}
                </select>
              </div>
              <div class="col-md-12 mb-3">
                <x-text-input name="tipo_localizacion" label="Tipo de localización" placeholder="Ej: terrestre, aéreo, mixto, etc..." :value="old('tipo_localizacion')" />
              </div>
            </div>
          </div>
        </div>

        <hr class="my-4">

        {{-- Cronología del conflicto --}}
        <div class="row">
          <div class="col-md-6">
            <x-date-input-group name="fecha_inicio" label="Fecha de inicio" :dia="old('dia_fecha_inicio')" :mes="old('mes_fecha_inicio')" :anno="old('anno_fecha_inicio')" />
          </div>
          <div class="col-md-6">
            <x-date-input-group name="fecha_fin" label="Fecha de conclusión" :dia="old('dia_fecha_fin')" :mes="old('mes_fecha_fin')" :anno="old('anno_fecha_fin')" />
          </div>
        </div>
      </div>
    </div>

    {{-- Panel de pestañas para información detallada --}}
    <div class="card card-dark card-outline card-tabs mt-4 shadow-sm">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="conflictoTab" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tab-relato"><i class="fas fa-book-open mr-1"></i> Desarrollo e historia</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-belico"><i class="fas fa-shield-halved mr-1"></i> Elementos bélicos</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-magia"><i class="fas fa-wand-sparkles mr-1"></i> Factores mágicos</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-beligerantes"><i class="fas fa-users mr-1"></i> Beligerantes</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-consecuencias"><i class="fas fa-flag-checkered mr-1"></i> Resultados</a></li>
        </ul>
      </div>
      <div class="card-body bg-white">
        <div class="tab-content">
          {{-- Pestaña relato --}}
          <div class="tab-pane fade show active" id="tab-relato">
            <x-textarea-input name="descripcion" label="Descripción general" :value="old('descripcion')" />
            <x-textarea-input name="preludio" label="Preludio y causas" :value="old('preludio')" />
            <x-textarea-input name="desarrollo" label="Desarrollo del conflicto" class="summernote" :value="old('desarrollo')" />
          </div>

          {{-- Pestaña bélica --}}
          <div class="tab-pane fade" id="tab-belico">
            <div class="row">
              <div class="col-md-6"><x-textarea-input name="unidades_especiales" label="Unidades militares especiales" :value="old('unidades_especiales')" /></div>
              <div class="col-md-6"><x-textarea-input name="criaturas_combate" label="Criaturas de combate" :value="old('criaturas_combate')" /></div>
              <div class="col-md-12"><x-textarea-input name="maquinaria_warlike" label="Maquinaria de guerra" :value="old('maquinaria_warlike')" /></div>
            </div>
          </div>

          {{-- Pestaña magia --}}
          <div class="tab-pane fade" id="tab-magia">
            <div class="custom-control custom-switch mb-3">
              <input type="checkbox" class="custom-control-input" id="es_conflicto_magico" name="es_conflicto_magico" value="1" {{ old('es_conflicto_magico') ? 'checked' : '' }}>
              <label class="custom-control-label" for="es_conflicto_magico">¿Involucró magia significativa?</label>
            </div>
            <div class="row">
              <div class="col-md-6"><x-textarea-input name="hechizos_decisivos" label="Hechizos decisivos" :value="old('hechizos_decisivos')" /></div>
              <div class="col-md-6"><x-textarea-input name="armas_magicas_empleadas" label="Artefactos y armas mágicas" :value="old('armas_magicas_empleadas')" /></div>
              <div class="col-md-6"><x-textarea-input name="seres_sobrenaturales_participantes" label="Seres sobrenaturales" :value="old('seres_sobrenaturales_participantes')" /></div>
              <div class="col-md-6"><x-textarea-input name="fenomenos_naturales" label="Fenómenos extraños/naturales" :value="old('fenomenos_naturales')" /></div>
            </div>
          </div>

          {{-- Pestaña beligerantes --}}
          <div class="tab-pane fade" id="tab-beligerantes">
            <div class="row">
              {{-- Bando Atacante --}}
              <div class="col-md-6">
                <div class="form-group">
                  <label for="personajes_atacantes" class="font-weight-bold">
                    <i class="fas fa-sword mr-1"></i> Líderes/Participantes Atacantes
                  </label>
                  <select name="personajes_atacantes[]" id="personajes_atacantes" class="form-control select2bs4" multiple="multiple" data-placeholder="Seleccionar atacantes...">
                    @foreach($personajes as $id => $nombre)
                    <option value="{{ $id }}" {{ (collect(old('personajes_atacantes'))->contains($id)) ? 'selected' : '' }}>
                      {{ $nombre }}
                    </option>
                    @endforeach
                  </select>
                  <small class="form-text text-muted">Personajes que iniciaron o lideraron la ofensiva.</small>
                </div>
              </div>

              {{-- Bando Defensor --}}
              <div class="col-md-6">
                <div class="form-group">
                  <label for="personajes_defensores" class="font-weight-bold">
                    <i class="fas fa-shield mr-1"></i> Líderes/Participantes Defensores
                  </label>
                  <select name="personajes_defensores[]" id="personajes_defensores" class="form-control select2bs4" multiple="multiple" data-placeholder="Seleccionar defensores...">
                    @foreach($personajes as $id => $nombre)
                    <option value="{{ $id }}" {{ (collect(old('personajes_defensores'))->contains($id)) ? 'selected' : '' }}>
                      {{ $nombre }}
                    </option>
                    @endforeach
                  </select>
                  <small class="form-text text-muted">Personajes que defendieron o resistieron.</small>
                </div>
              </div>
            </div>
            <div class="row">
              {{-- Bando paises atacantes --}}
              <div class="col-md-6">
                <div class="form-group">
                  <label for="paises_atacantes" class="font-weight-bold">
                    <i class="fas fa-sword mr-1"></i> Países atacantes
                  </label>
                  <select name="paises_atacantes[]" id="paises_atacantes" class="form-control select2bs4" multiple="multiple" data-placeholder="Seleccionar atacantes...">
                    @foreach($paises as $id => $nombre)
                    <option value="{{ $id }}" {{ (collect(old('paises_atacantes'))->contains($id)) ? 'selected' : '' }}>
                      {{ $nombre }}
                    </option>
                    @endforeach
                  </select>
                  <small class="form-text text-muted">Países que iniciaron o lideraron la ofensiva.</small>
                </div>
              </div>

              {{-- Bando paises defensor --}}
              <div class="col-md-6">
                <div class="form-group">
                  <label for="paises_defensores" class="font-weight-bold">
                    <i class="fas fa-shield mr-1"></i> Países defensores
                  </label>
                  <select name="paises_defensores[]" id="paises_defensores" class="form-control select2bs4" multiple="multiple" data-placeholder="Seleccionar defensores...">
                    @foreach($paises as $id => $nombre)
                    <option value="{{ $id }}" {{ (collect(old('paises_defensores'))->contains($id)) ? 'selected' : '' }}>
                      {{ $nombre }}
                    </option>
                    @endforeach
                  </select>
                  <small class="form-text text-muted">Países que defendieron o resistieron.</small>
                </div>
              </div>
            </div>
          </div>

          {{-- Pestaña resultados --}}
          <div class="tab-pane fade" id="tab-consecuencias">
            <x-text-input name="vencedor_texto" label="Vencedor (Resumen)" placeholder="Ej: Coalición del Norte" :value="old('vencedor_texto')" />
            <x-textarea-input name="resultado" label="Resultado" :value="old('resultado')" />
            <x-textarea-input name="consecuencias" label="Consecuencias geopolíticas" :value="old('consecuencias')" />
            <x-textarea-input name="otros" label="Notas adicionales" :value="old('otros')" />
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('specific-scripts')
<script>
    // Inyectamos los datos del servidor a variables de JS
    window.ubicacionesData = {
        "App\\Models\\Asentamiento": {!! $asentamientos->toJson() !!},
        "App\\Models\\Lugar": {!! $lugares->toJson() !!}
    };
    // Pasamos el ID antiguo para mantener la selección en caso de error de validación
    window.selectedUbicacionId = "{{ old('ubicacion_principal_id') }}";
</script>
<script src="{{asset('dist/js/common.js')}}"></script>
<script src="{{asset('dist/js/updateUbicacionOptions.js')}}"></script>
@endsection