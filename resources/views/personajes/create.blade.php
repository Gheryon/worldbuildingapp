@extends('layouts.index')

@section('title')
<title id="title">Nuevo personaje</title>
@endsection

@section('navbar-buttons')
<li class="nav-item ml-2">
  <a href="{{route('personajes.index')}}" class="btn btn-dark">Cancelar</a>
</li>
@endsection

@section('content')
<div class="row">
  <div class="col text-center">
    <h1>Nuevo personaje</h1>
  </div>
</div>
<hr>

<!-- Main content -->
<section class="content">
  <form id="form-create-personaje" class="position-relative needs-validation" action="{{route('personajes.store')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row justify-content-md-center">
      <div class="col-md-auto form-actions">
        <button type="submit" id="submit-crear-button" class="btn btn-success px-5 shadow-sm">Guardar personaje</button>
      </div>
    </div>

    {{-- Sección de datos básicos y retrato --}}
    <div class="card card-outline card-dark mt-3">
      <div class="card-body">
        <div class="row">
          <div class="col-md-9">
            <div class="row">
              <div class="col-md-3">
                <x-text-input name="nombre" label="Nombre" placeholder="Ej: Aria, Nicanor, etc." icon="fa-user" required />
              </div>
              <div class="col-md-3">
                <x-text-input name="nombre_familia" label="Nombre de familia o clan" placeholder="Ej: Cervantes, Fernández, etc." icon="fa-users" />
              </div>
              <div class="col-md-3">
                <x-text-input name="apellidos" label="Apellidos" placeholder="Ej: García López, Sánchez, etc." icon="fa-signature" />
              </div>
              <div class="col-md-3">
                <x-text-input name="apodo" label="Apodo" placeholder="Ej: El Veloz, El Sabio, etc." icon="fa-user-tag" />
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-2">
                <label for="sexo"><i class="fas fa-venus-mars mr-1"></i>Sexo</label>
                <select class="form-control @error('sexo') is-invalid @enderror mt-2" name="sexo" id="sexo" required>
                  <option selected disabled value="">Elegir</option>
                  <option {{ old('sexo') == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                  <option {{ old('sexo') == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                </select>
                @error('sexo') <small class="text-danger d-block">{{ $message }}</small> @enderror
              </div>
              <div class="col-md-3">
                <label for="select_especie"><i class="fas fa-paw mr-1"></i>Especie</label>
                @if(isset($especies) && count($especies) > 0)
                <select class="form-control @error('select_especie') is-invalid @enderror mt-2" name="select_especie" id="select_especie" required>
                  <option value="" selected disabled>Elegir una especie</option>
                  @foreach($especies as $id => $nombre)
                  <option value="{{ $id }}" {{ old('select_especie') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
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
                <x-text-input name="lugar_nacimiento" label="Lugar de nacimiento" placeholder="Ej: Córdoba, Minas Tirith." icon="fa-map-marker-alt" disabled />
              </div>
              <div class="col-md">
                <x-text-input name="profesion" label="Profesión" placeholder="Ej: Alquimista, guerrero, etc." icon="fa-briefcase" />
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-4">
                <x-date-input-group name="nacimiento" label="Fecha de nacimiento" icon="fa-calendar-plus" />
              </div>
              <div class="col-md-4">
                <x-date-input-group name="fallecimiento" label="Fecha de fallecimiento" icon="fa-calendar-times" />
              </div>
              <div class="col-md">
                <x-text-input name="causa_fallecimiento" label="Causa de fallecimiento" placeholder="Ej: Enfermedad, accidente, asesinato..." icon="fa-skull-crossbones" />
              </div>
            </div>
          </div>
          <div class="col-md-3 text-center border-left">
            <label for="retrato"><i class="fas fa-image mr-1"></i>Retrato</label>
            <div class="mb-2">
              <img id="retrato-preview" src="{{asset('storage/retratos/default.png')}}" class="img-fluid" style="width: 200px; height: 200px; object-fit: cover;">
            </div>
            <input type="file" name="retrato" class="form-control @error('retrato') is-invalid @enderror" id="retrato">
            @error('retrato') <small class="text-danger d-block">{{ $message }}</small> @enderror
          </div>
        </div>
      </div>
    </div>{{-- Fin sección de datos básicos y retrato --}}

    {{-- Campo de descripción breve --}}
    <div class="card card-dark card-outline card-tabs mt-4">
      <div class="card-body">
        <x-textarea-input name="descripcion_corta" label="Descripción breve" rows="2" icon="fa-feather-alt" />
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

          {{-- PESTAÑA 1: Físico, Salud, Personalidad, Deseos, Miedos, Magia --}}
          <div class="tab-pane fade show active" id="tab-fisico" role="tabpanel">
            <div class="row">
              <div class="col-md-6">
                <x-textarea-input name="descripcion" label="Descripción física" icon="fa-user" />
                <x-textarea-input name="salud" label="Enfermedades, heridas o problemas de salud" icon="fa-heartbeat" />
                <x-textarea-input name="personalidad" label="Personalidad" icon="fa-brain" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="deseos" label="Principales deseos" icon="fa-star" />
                <x-textarea-input name="miedos" label="Principales miedos" icon="fa-ghost" />
                <x-textarea-input name="magia" label="Habilidades Mágicas" icon="fa-magic" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 2: Educación, Religión, Familia, Política --}}
          <div class="tab-pane fade" id="tab-social" role="tabpanel">
            <x-textarea-input name="educacion" label="Educación y cultura" icon="fa-graduation-cap" />
            <x-textarea-input name="religion" label="Religión" icon="fa-monument" />
            <x-textarea-input name="familia" label="Familia y relaciones" icon="fa-users" />
            <x-textarea-input name="politica" label="Política y títulos" icon="fa-gavel" />
          </div>

          {{-- PESTAÑA 3: Historia y Otros --}}
          <div class="tab-pane fade" id="tab-historia" role="tabpanel">
            <x-textarea-input name="biografia" label="Historia" class="summernote" rows="10" icon="fa-scroll" />
            <x-textarea-input name="otros" label="Otros detalles adicionales" class="summernote-lite" icon="fa-plus-circle" />
          </div>
        </div>
      </div>
    </div>{{-- Fin panel de pestañas --}}

    <x-reference-images-manager />

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
        document.getElementById('retrato-preview').src = URL.createObjectURL(file)
      }
    }
  });
</script>
@endsection