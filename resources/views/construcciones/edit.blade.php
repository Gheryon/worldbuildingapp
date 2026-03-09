@extends('layouts.index')

@section('title')
<title id="title">Editar {{$construccion->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
<a href="{{route('construcciones.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Editar {{$construccion->nombre}}</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
<form id="form-create-construccion" data-prevent-loss="true" class="needs-validation" action="{{route('construccion.update', $construccion->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- Botón de Acción Superior --}}
    <div class="row mb-3">
      <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success btn-lg shadow-sm">
          <i class="fas fa-plus-circle mr-2"></i> Guardar Construcción
        </button>
      </div>
    </div>

    {{-- Bloque de Datos Primarios --}}
    <div class="card card-outline card-dark shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-hammer mr-1"></i> Información técnica y estado</h3>
      </div>
      <div class="card-body bg-light">
        <div class="row">
          {{-- Columna de Identidad --}}
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6 mb-3">
                <x-text-input name="nombre" label="Nombre de la construcción" placeholder="Ej: Gran Faro de Pharos" :value="old('nombre', $construccion->nombre)" required />
              </div>
              <div class="col-md-3">
                <x-text-input name="altitud" label="Altura" type="number" :value="old('altitud', $construccion->altitud)" />
              </div>
              <div class="col-md-3 mb-3">
                <label for="tipo_construccion_id" class="form-label font-weight-bold mt-2">Tipo</label>
                <select class="form-control select2bs4" name="tipo_construccion_id" id="tipo_construccion_id" required>
                  <option selected disabled value="">Elegir tipo...</option>
                  @foreach($tipos as $tipo)
                  <option value="{{$tipo->id}}" {{ old('tipo_construccion_id', $construccion->tipo_construccion_id) == $tipo->id ? 'selected' : '' }}>{{$tipo->nombre}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="estatus" class="form-label font-weight-bold">Estatus actual</label>
                <select class="form-control select2bs4" name="estatus" id="estatus" required>
                  <option selected value="">Elegir...</option>
                  @foreach(['Abandonado','Destruido','En construcción','En pie','En ruinas','Enterrado','Habitado','Secreto','Olvidado'] as $est)
                  <option value="{{ $est }}" {{ old('estatus', $construccion->estatus) == $est ? 'selected' : '' }}>{{ $est }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label for="nivel_deterioro" class="form-label font-weight-bold">Nivel de deterioro</label>
                <select class="form-control select2bs4" name="nivel_deterioro" id="nivel_deterioro">
                  @foreach(['Ninguno', 'Bajo', 'Medio', 'Alto', 'Irreversible'] as $det)
                  <option value="{{ $det }}" {{ old('nivel_deterioro', $construccion->nivel_deterioro) == $det ? 'selected' : '' }}>{{ $det }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label for="dificultad_acceso" class="form-label font-weight-bold">Dificultad de acceso</label>
                <select class="form-control select2bs4" name="dificultad_acceso" id="dificultad_acceso">
                  @foreach(['Libre', 'Fácil', 'Moderada', 'Difícil', 'Extrema'] as $dif)
                  <option value="{{ $dif }}" {{ old('dificultad_acceso', $construccion->dificultad_acceso) == $dif ? 'selected' : '' }}>{{ $dif }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          {{-- Columna de control y ubicación --}}
          <div class="col-md-4 border-left">
            <div class="mb-3">
              <label for="asentamiento_id" class="form-label font-weight-bold">Ubicado en:</label>
              <select class="form-control select2bs4" name="asentamiento_id" id="asentamiento_id">
                <option value="" selected>Desconocido / Exterior</option>
                @foreach($asentamientos as $id => $nombre)
                <option value="{{$id}}" {{ old('asentamiento_id', $construccion->asentamiento_id) == $id ? 'selected' : '' }}>{{$nombre}}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3 text-center">
              <label class="form-label font-weight-bold d-block">Configuración</label>
              <div class="d-flex flex-wrap justify-content-center gap-2">
                <div class="custom-control custom-checkbox mr-3">
                  <input class="custom-control-input" type="checkbox" id="acceso_publico" name="acceso_publico" value="1" {{ old('acceso_publico', $construccion->acceso_publico) ? 'checked' : '' }}>
                  <label for="acceso_publico" class="custom-control-label">Acceso público</label>
                </div>
                <div class="custom-control custom-checkbox mr-3">
                  <input class="custom-control-input" type="checkbox" id="acceso_temporal" name="acceso_temporal" value="1" {{ old('acceso_temporal', $construccion->acceso_temporal) ? 'checked' : '' }}>
                  <label for="acceso_temporal" class="custom-control-label">Acceso temporal</label>
                </div>
                <div class="custom-control custom-checkbox mr-3">
                  <input class="custom-control-input" type="checkbox" id="tecnologia_perdida" name="tecnologia_perdida" value="1" {{ old('tecnologia_perdida', $construccion->tecnologia_perdida) ? 'checked' : '' }}>
                  <label for="tecnologia_perdida" class="custom-control-label">Tecnología perdida</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <hr class="my-4">

        {{-- Cronología --}}
        <div class="row">
          <div class="col-md-6">
            <x-date-input-group name="fecha_construccion" label="Fecha de construcción" :dia="old('dia_construccion', $construccion->fechaConstruccion->dia ?? '')" :mes="old('mes_construccion', $construccion->fechaConstruccion->mes ?? '')" :anno="old('anno_construccion', $construccion->fechaConstruccion->anno ?? '')" />
          </div>
          <div class="col-md-6">
            <x-date-input-group name="fecha_destruccion" label="Fecha de destrucción/abandono" :dia="old('dia_destruccion', $construccion->fechaDestruction->dia ?? '')" :mes="old('mes_destruccion', $construccion->fechaDestruction->mes ?? '')" :anno="old('anno_destruccion', $construccion->fechaDestruction->anno ?? '')" />
          </div>
        </div>
      </div>
    </div>

    {{-- Panel de Pestañas --}}
    <div class="card card-dark card-outline card-tabs mt-4 shadow-sm">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="construccionTab" role="tablist">
          <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tab-diseno"><i class="fas fa-drafting-compass mr-1"></i> Diseño y Materiales</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-historia"><i class="fas fa-scroll mr-1"></i> Historia y Propósito</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-magia"><i class="fas fa-magic mr-1"></i> Propiedades Mágicas</a></li>
          <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-acceso"><i class="fas fa-map-marked-alt mr-1"></i> Logística y Acceso</a></li>
        </ul>
      </div>
      <div class="card-body bg-white">
        <div class="tab-content">
          {{-- Pestaña Diseño --}}
          <div class="tab-pane fade show active" id="tab-diseno">
            <div class="row">
              <div class="col-12 mb-3"><x-textarea-input name="descripcion_breve" label="Descripción breve" :value="old('descripcion_breve', $construccion->descripcion_breve)" /></div>
              <div class="col-md-6"><x-textarea-input name="arquitectura" label="Estilo arquitectónico" :value="old('arquitectura', $construccion->arquitectura)" /></div>
              <div class="col-md-6"><x-textarea-input name="materiales_principales" label="Materiales principales" :value="old('materiales_principales', $construccion->materiales_principales)" /></div>
              <div class="col-md-6"><x-textarea-input name="tecnica_construccion" label="Técnicas de edificación" :value="old('tecnica_construccion', $construccion->tecnica_construccion)" /></div>
              <div class="col-md-6"><x-textarea-input name="materiales_exoticos" label="Componentes extraños o raros" :value="old('materiales_exoticos', $construccion->materiales_exoticos)" /></div>
            </div>
          </div>
          {{-- Pestaña Historia --}}
          <div class="tab-pane fade" id="tab-historia">
            <x-textarea-input name="proposito" label="Propósito original de la obra" :value="old('proposito', $construccion->proposito)" />
            <x-textarea-input name="importancia_social" label="Relevancia cultural e importancia social" :value="old('importancia_social', $construccion->importancia_social)" />
            <x-textarea-input name="historia" label="Historia" class="summernote" rows="12" :value="old('historia', $construccion->historia)" />
          </div>
          {{-- Pestaña Magia --}}
          <div class="tab-pane fade" id="tab-magia">
            <div class="row mb-3">
              <div class="col-md-4">
                <div class="custom-control custom-switch mt-2">
                  <input type="checkbox" class="custom-control-input" id="tiene_magia_inherente" name="tiene_magia_inherente" value="1" {{ old('tiene_magia_inherente', $construccion->tiene_magia_inherente) ? 'checked' : '' }}>
                  <label class="custom-control-label" for="tiene_magia_inherente">¿Construcción mágica?</label>
                </div>
              </div>
              <div class="col-md-8">
                <x-text-input name="tipo_magia" label="Tipo de magia / Escuela" placeholder="Ej: Abjuración, Divina, Ancestral..." :value="old('tipo_magia', $construccion->tipo_magia)" />
              </div>
            </div>
            <x-textarea-input name="propiedades_magicas" label="Propiedades y efectos mágicos" :value="old('propiedades_magicas', $construccion->propiedades_magicas)" />
            <x-textarea-input name="fuente_poder_magico" label="Origen o fuente del poder" :value="old('fuente_poder_magico', $construccion->fuente_poder_magico)" />
            <x-textarea-input name="simbolismo" label="Simbolismo y esoterismo" :value="old('simbolismo', $construccion->simbolismo)" />
          </div>
          {{-- Pestaña Acceso --}}
          <div class="tab-pane fade" id="tab-acceso">
            <x-textarea-input name="rutas_acceso" label="Rutas de acceso conocidas" :value="old('rutas_acceso', $construccion->rutas_acceso)" />
            <x-textarea-input name="aspecto" label="Notas sobre el aspecto externo actual" :value="old('aspecto', $construccion->aspecto)" />
            <x-textarea-input name="otros" label="Notas adicionales" :value="old('otros', $construccion->otros)" />
          </div>
        </div>
      </div>
    </div>
  </form>

</section>
<!-- /.content -->
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
@endsection