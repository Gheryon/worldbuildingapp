@extends('layouts.index')

@section('title')
<title id="title">Editar {{$asentamiento->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('asentamientos.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="container-fluid">
  {{-- Encabezado estilizado --}}
  <div class="row mb-4">
    <div class="col-12 text-center">
      <h1 class="display-4 text-primary-custom font-weight-bold">
        <i class="nav-icon fa-solid fa-house mr-2"></i>Editar {{$asentamiento->nombre}}
      </h1>
    </div>
  </div>

  <form id="form-edit-asentamiento" data-prevent-loss="true" class="needs-validation" action="{{route('asentamientos.update', $asentamiento->id )}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Fila de Acciones Flotante o Superior --}}
    <div class="row mb-3">
      <div class="col-12 d-flex justify-content-end">
        <button type="submit" id="submit-crear-button" class="btn btn-success btn-lg shadow-sm">
          <i class="fas fa-save mr-2"></i> Guardar Cambios
        </button>
      </div>
    </div>

    <div class="card card-outline card-dark shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Información técnica</h3>
      </div>
      <div class="card-body bg-light">
        <div class="row">
          {{-- Bloque Principal --}}
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6 mb-3">
                <x-text-input name="nombre" label="Nombre del asentamiento" placeholder="Ej: Córdoba, Minas Tirith, etc." :value="$asentamiento->nombre" icon="fa-city" required />
              </div>
              <div class="col-md-6 mb-3">
                <x-text-input name="gentilicio" label="Gentilicio" placeholder="Ej: Cordobés, etc." :value="$asentamiento->gentilicio" icon="fa-user-tag" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="select_tipo" class="form-label font-weight-bold"><i class="fas fa-tags mr-1"></i>Tipo</label>
                <select class="form-control select2bs4 @error('select_tipo') is-invalid @enderror" name="select_tipo" id="select_tipo" required>
                  <option selected disabled value="">Elegir...</option>
                  @foreach($tipos_asentamientos as $tipo)
                  <option value="{{$tipo->id}}" {{ old('select_tipo', $asentamiento->tipo_asentamiento_id) == $tipo->id ? 'selected' : '' }}>{{$tipo->nombre}}</option>
                  @endforeach
                </select>
                @error('select_tipo') <small class="text-danger d-block">{{ $message }}</small> @enderror
              </div>
              <div class="col-md-4 mb-3">
                <label for="estatus" class="form-label font-weight-bold"><i class="fas fa-flag mr-1"></i>Estatus actual</label>
                <select class="form-control select2bs4 @error('estatus') is-invalid @enderror" name="estatus" id="estatus" required>
                  <option selected value="">Elegir...</option>
                  @foreach(['Abandonado', 'En ruinas', 'Habitado', 'Secreto', 'Olvidado'] as $est)
                  <option value="{{ $est }}" {{ old('estatus', $asentamiento->estatus) == $est ? 'selected' : '' }}>{{ $est }}</option>
                  @endforeach
                </select>
                @error('estatus') <small class="text-danger d-block">{{ $message }}</small> @enderror
              </div>
              <div class="col-md-4 mb-3">
                <label for="poblacion" class="form-label font-weight-bold"><i class="fas fa-users mr-1"></i>Población estimada</label>
                <div class="input-group">
                  <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-users"></i></span></div>
                  <input type="number" name="poblacion" class="form-control @error('poblacion') is-invalid @enderror" id="poblacion" placeholder="Ej: 5000" value="{{ old('poblacion', $asentamiento->poblacion) }}">
                </div>
                @error('poblacion') <small class="text-danger d-block">{{ $message }}</small> @enderror
              </div>
            </div>
          </div>

          {{-- Bloque de Control --}}
          <div class="col-md-4 border-left">
            <div class="mb-3">
              <label for="select_owner" class="form-label font-weight-bold"><i class="fas fa-landmark mr-1"></i>Controlado por:</label>
              <select class="form-control select2bs4 @error('select_owner') is-invalid @enderror" name="select_owner" id="select_owner">
                <option value="">Independiente / Ninguno</option>
                @foreach($paises as $id => $nombre)
                <option value="{{$id}}" {{ old('select_owner', $asentamiento->organizacion_id) == $id ? 'selected' : '' }}>{{$nombre}}</option>
                @endforeach
              </select>
              @error('select_owner') <small class="text-danger d-block">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3">
              <label for="select_gobernante" class="form-label font-weight-bold"><i class="fas fa-user-tie mr-1"></i>Gobernante local</label>
              <select class="form-control select2bs4 @error('select_gobernante') is-invalid @enderror" name="select_gobernante" id="select_gobernante">
                <option value="">Sin gobernante especificado</option>
                @foreach($personajes as $id => $nombre)
                <option value="{{$id}}" {{ old('select_gobernante', $asentamiento->gobernante_id) == $id ? 'selected' : '' }}>{{$nombre}}</option>
                @endforeach
              </select>
              @error('select_gobernante') <small class="text-danger d-block">{{ $message }}</small> @enderror
            </div>
          </div>
        </div>

        <hr class="my-4">

        {{-- Fila de Fechas --}}
        <div class="row">
          <div class="col-md-6">
            <x-date-input-group name="fundacion" label="Fecha de fundación" :id="$asentamiento->fundacion_id" :dia="$asentamiento->fecha_fundacion->dia ?? ''" :mes="$asentamiento->fecha_fundacion->mes ?? ''" :anno="$asentamiento->fecha_fundacion->anno ?? ''" icon="fa-calendar-plus" />
          </div>
          <div class="col-md-6">
            <x-date-input-group name="disolucion" label="Fecha de disolución" :id="$asentamiento->disolucion_id" :dia="$asentamiento->fecha_disolucion->dia ?? ''" :mes="$asentamiento->fecha_disolucion->mes ?? ''" :anno="$asentamiento->fecha_disolucion->anno ?? ''" icon="fa-calendar-times" />
          </div>
        </div>
      </div>
    </div>

    {{-- Panel de pestañas para Textareas --}}
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
              <div class="col-md-12 mb-3">
                <x-textarea-input name="descripcion" label="Descripción general" :value="$asentamiento->descripcion" icon="fa-align-left" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="geografia" label="Geografía" :value="$asentamiento->geografia" icon="fa-globe-americas" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="clima" label="Clima" :value="$asentamiento->clima" icon="fa-cloud-sun" />
              </div>
              <div class="col-md-12">
                <x-textarea-input name="ubicacion_detalles" label="Detalles especiales o secretos" :value="$asentamiento->ubicacion_detalles" icon="fa-key" />
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="tab-social">
            <x-textarea-input name="demografia" label="Composición demográfica" :value="$asentamiento->demografia" icon="fa-users" />
            <x-textarea-input name="cultura" label="Tradiciones, costumbres y cultura" :value="$asentamiento->cultura" icon="fa-theater-masks" />
            <x-textarea-input name="arquitectura" label="Arquitectura y monumentos" :value="$asentamiento->arquitectura" icon="fa-monument" />
            <x-textarea-input name="infraestructura" label="Servicios e infraestructura" :value="$asentamiento->infraestructura" icon="fa-tools" />
          </div>
          <div class="tab-pane fade" id="tab-politica">
            <x-textarea-input name="gobierno" label="Sistema de gobierno" :value="$asentamiento->gobierno" icon="fa-gavel" />
            <x-textarea-input name="defensas" label="Murallas y fortificaciones" :value="$asentamiento->defensas" icon="fa-shield-alt" />
            <x-textarea-input name="ejercito" label="Guarnición y fuerzas militares" :value="$asentamiento->ejercito" icon="fa-fist-raised" />
          </div>
          <div class="tab-pane fade" id="tab-economia">
            <div class="row">
              <div class="col-md-6"><x-text-input name="recurso_principal" label="Recurso principal" placeholder="Ej: Hierro, carbón, etc." :value="$asentamiento->recurso_principal" icon="fa-gem" /></div>
              <div class="col-md-6"><x-text-input name="nivel_riqueza" label="Nivel de riqueza" placeholder="Ej: Bajo, medio, alto, etc." :value="$asentamiento->nivel_riqueza" icon="fa-coins" /></div>
            </div>
            <x-textarea-input name="economia" label="Economía, industria y comercio" :value="$asentamiento->economia" icon="fa-industry" />
            <x-textarea-input name="recursos" label="Recursos naturales" :value="$asentamiento->recursos" icon="fa-leaf" />
          </div>
          <div class="tab-pane fade" id="tab-historia">
            <x-textarea-input name="historia" label="Historia del asentamiento" class="summernote" rows="12" :value="$asentamiento->historia" icon="fa-scroll" />
            <x-textarea-input name="otros" label="Notas adicionales" :value="$asentamiento->otros" icon="fa-plus-circle" />
          </div>
        </div>
      </div>
    </div>{{-- Fin panel de pestañas --}}

    <x-reference-images-manager :imagenes="$asentamiento->imagenes" entityType="asentamientos" :entityId="$asentamiento->id" />
  </form>
</div>
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
@endsection