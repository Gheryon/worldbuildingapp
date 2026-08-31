@extends('layouts.index')

@section('title')
<title id="title">Editar {{$personaje->nombre}}</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('personajes.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Editar {{$personaje->nombre}}</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
    <form id="form-edit-personaje" class="position-relative needs-validation" action="{{route('personajes.update', $personaje->id )}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row justify-content-md-center">
      <div class="col-md-auto form-actions">
        <button type="submit" id="submit-crear-button" class="btn btn-success px-5 shadow-sm">Guardar</button>
      </div>
    </div>

    {{-- Sección de datos básicos y retrato --}}
    <div class="card card-outline card-dark mt-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-9">
            <div class="row mt-2">
              <div class="col-md">
                <x-text-input name="nombre" label="Nombre" :value="$personaje->nombre" icon="fa-user" required />
              </div>
              <div class="col-md">
                <x-text-input name="nombre_familia" label="Nombre de familia" :value="$personaje->nombre_familia" placeholder="Ej: Cervantes, Fernández, etc." icon="fa-users" />
              </div>
              <div class="col-md">
                <x-text-input name="apellidos" label="Apellidos" :value="$personaje->apellidos" placeholder="Ej: García López, Sánchez, etc." icon="fa-signature" />
              </div>
              <div class="col-md-4">
                <x-text-input name="apodo" label="Apodo" :value="$personaje->apodo" placeholder="Ej: El Veloz, El Sabio, etc." icon="fa-user-tag" />
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-2">
                <label for="sexo"><i class="fas fa-venus-mars mr-1"></i>Sexo</label>
                <select class="form-control @error('sexo') is-invalid @enderror mt-2" name="sexo" id="sexo" required>
                  <option selected disabled value="">Elegir</option>
                  <option {{ old('sexo', $personaje->sexo) == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                  <option {{ old('sexo', $personaje->sexo) == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                </select>
                @error('sexo') <small class="text-danger d-block">{{ $message }}</small> @enderror
              </div>
              <div class="col-md-3">
                <label for="select_especie"><i class="fas fa-paw mr-1"></i>Especie</label>
                @if(isset($especies) && count($especies) > 0)
                <select class="form-control @error('select_especie') is-invalid @enderror mt-2" name="select_especie" id="select_especie" required>
                  <option value="" selected disabled>Elegir una especie</option>
                  @foreach($especies as $id => $nombre)
                  <option value="{{ $id }}" {{ $personaje->especie_id == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                  @endforeach
                </select>
                @else
                <div class="alert alert-warning p-1 mb-0" style="font-size: 0.85rem;">
                  <i class="fas fa-exclamation-triangle mr-1"></i>
                  No se encontraron especies en el sistema.
                </div>
                <input type="hidden" name="select_especie" value="">
                @endif
                @error('select_especie')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="col-md">
                <x-text-input name="lugar_nacimiento" label="Lugar de nacimiento" :value="$personaje->lugar_nacimiento" placeholder="Lugar de nacimiento" icon="fa-map-marker-alt" disabled/>
              </div>
              <div class="col-md">
                <x-text-input name="profesion" label="Profesión" :value="$personaje->profesion" placeholder="Ej: Alquimista, guerrero, etc." icon="fa-briefcase" />
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-4">
                <x-date-input-group name="nacimiento" label="Fecha de nacimiento" :id="$personaje->nacimiento_id" :dia="$personaje->fecha_nacimiento->dia ?? ''" :mes="$personaje->fecha_nacimiento->mes ?? ''" :anno="$personaje->fecha_nacimiento->anno ?? ''" icon="fa-calendar-plus" />
              </div>
              <div class="col-md-4">
                <x-date-input-group name="fallecimiento" label="Fecha de fallecimiento" :id="$personaje->fallecimiento_id" :dia="$personaje->fecha_fallecimiento->dia ?? ''" :mes="$personaje->fecha_fallecimiento->mes ?? ''" :anno="$personaje->fecha_fallecimiento->anno ?? ''" icon="fa-calendar-times" />
              </div>
              <div class="col-md">
                <x-text-input name="causa_fallecimiento" label="Causa de fallecimiento" :value="$personaje->causa_fallecimiento" placeholder="Ej: Enfermedad, accidente, asesinato..." icon="fa-skull-crossbones"/>
              </div>
            </div>
          </div>
        <div class="col-md-3 mt-2 mb-2">
          <label for="retrato" class="form-label"><i class="fas fa-image mr-1"></i>Retrato</label>
          <img alt="retrato" id="retrato-img" src="{{asset("storage/retratos/{$personaje->retrato}")}}" class="img-fluid" width="185" height="180">
          <input type="file" name="retrato" class="form-control @error('retrato') is-invalid @enderror" id="retrato">
          @error('retrato') <small class="text-danger d-block">{{ $message }}</small> @enderror
        </div>
        </div>
      </div>
    </div>{{-- Fin sección de datos básicos y retrato --}}

    {{-- Descripción breve --}}
    <div class="card card-dark card-outline mt-4">
      <div class="card-body">
        <x-textarea-input name="descripcion_corta" label="Descripción breve" :value="$personaje->descripcion_corta" rows="2" icon="fa-feather-alt" />
      </div>
    </div>

    {{-- Panel de pestañas --}}
    <div class="card card-dark card-outline card-tabs mt-4">
      <div class="card-header p-0 pt-1 border-bottom-0">
        <ul class="nav nav-tabs" id="personajeTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="fisico-tab" data-toggle="pill" href="#tab-fisico" role="tab">Físico y Psicología</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="social-tab" data-toggle="pill" href="#tab-social" role="tab">Cultura y Sociedad</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="historia-tab" data-toggle="pill" href="#tab-historia" role="tab">Historia y Otros</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content" id="personajeTabContent">

          {{-- PESTAÑA 1 --}}
          <div class="tab-pane fade show active" id="tab-fisico" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="descripcion_fisica" label="Descripción física" :value="$personaje->descripcion_fisica" icon="fa-user" />
                <x-textarea-input name="salud" label="Salud" :value="$personaje->salud" icon="fa-heartbeat" />
                <x-textarea-input name="personalidad" label="Personalidad" :value="$personaje->personalidad" icon="fa-brain" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="deseos" label="Principales deseos" :value="$personaje->deseos" icon="fa-star" />
                <x-textarea-input name="miedos" label="Principales miedos" :value="$personaje->miedos" icon="fa-ghost" />
                <x-textarea-input name="magia" label="Habilidades Mágicas" :value="$personaje->magia" icon="fa-magic" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 2 --}}
          <div class="tab-pane fade" id="tab-social" role="tabpanel">
            <x-textarea-input name="educacion" label="Educación y cultura" :value="$personaje->educacion" icon="fa-graduation-cap" />
            <x-textarea-input name="religion" label="Religión" :value="$personaje->religion" icon="fa-monument" />
            <x-textarea-input name="familia" label="Familia" :value="$personaje->familia" icon="fa-users" />
            <x-textarea-input name="politica" label="Política y títulos" :value="$personaje->politica" icon="fa-gavel" />
          </div>

          {{-- PESTAÑA 3 --}}
          <div class="tab-pane fade" id="tab-historia" role="tabpanel">
            <x-textarea-input name="biografia" label="Historia" :value="$personaje->biografia" class="summernote" rows="10" icon="fa-scroll" />
            <x-textarea-input name="otros" label="Otros detalles" :value="$personaje->otros" icon="fa-plus-circle" />
          </div>
        </div>
      </div>
    </div>{{-- Fin panel de pestañas --}}

    <x-reference-images-manager :imagenes="$personaje->imagenes" entityType="personajes" :entityId="$personaje->id" />

  </form>

</section>
<!-- /.content -->
@endsection

@section('specific-scripts')
<script src="{{asset('dist/js/common.js')}}"></script>
<script>
  $(function() {
    //Preview de retrato antes de subirla
    document.getElementById('retrato').onchange = evt => {
      const [file] = document.getElementById('retrato').files
      if (file) {
        document.getElementById('retrato-img').src = URL.createObjectURL(file)
      }
    }
  });
</script>
@endsection