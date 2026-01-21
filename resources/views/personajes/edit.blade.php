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
  <form id="form-edit-personaje" class="position-relative needs-validation" action="{{route('personaje.update', $personaje->id )}}" method="post" enctype="multipart/form-data">
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
                <x-text-input name="nombre" label="Nombre" :value="$personaje->nombre" required />
              </div>
              <div class="col-md">
                <x-text-input name="nombre_familia" label="Nombre de familia" :value="$personaje->nombre_familia" placeholder="Ej: Cervantes, Fernández, etc." />
              </div>
              <div class="col-md">
                <x-text-input name="apellidos" label="apellidos" :value="$personaje->apellidos" placeholder="Ej: García López, Sánchez, etc." />
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-3">
                <label for="sexo">Sexo</label>
                <select class="form-control" name="sexo" id="sexo" required>
                  <option selected disabled value="">Elegir</option>
                  <option {{ $personaje->sexo == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                  <option {{ $personaje->sexo == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                </select>
                @error('sexo')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="col-md-3">
                <label for="select_especie">Especie</label>
                @if(isset($especies) && count($especies) > 0)
                {{-- Caso exitoso: Hay especies disponibles --}}
                <select class="form-control @error('select_especie') is-invalid @enderror" name="select_especie" id="select_especie" required>
                  <option value="" selected disabled>Elegir una especie</option>
                  @foreach($especies as $especie)
                  <option value="{{ $especie->id }}" {{ $personaje->id_foranea_especie == $especie->id ? 'selected' : '' }}>{{ $especie->nombre }}</option>
                  @endforeach
                </select>
                @else
                {{-- Error: La variable no existe o la colección está vacía --}}
                <div class="alert alert-warning p-1 mb-0" style="font-size: 0.85rem;">
                  <i class="fas fa-exclamation-triangle mr-1"></i>
                  No se encontraron especies en el sistema.
                </div>
                <input type="hidden" name="select_especie" value="">
                @endif

                {{-- Error de Validación de Laravel --}}
                @error('select_especie')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>
              <div class="col-md">
                <x-text-input name="lugar_nacimiento" label="Lugar de nacimiento" :value="$personaje->lugarNacimiento" placeholder="Lugar de nacimiento" />
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-4">
                <x-date-input-group name="nacimiento" label="Fecha de nacimiento" :id="$personaje->nacimiento" :dia="$nacimiento->dia ?? ''" :mes="$nacimiento->mes ?? ''" :anno="$nacimiento->anno ?? ''" />
              </div>
              <div class="col-md-4">
                <x-date-input-group name="fallecimiento" label="Fecha de fallecimiento" :id="$personaje->fallecimiento" :dia="$fallecimiento->dia ?? ''" :mes="$fallecimiento->mes ?? ''" :anno="$fallecimiento->anno ?? ''" />
              </div>
              <div class="col-md">
                <x-text-input name="causa_fallecimiento" label="Causa de fallecimiento" :value="$personaje->causa_fallecimiento" placeholder="Ej: Enfermedad, accidente, asesinato..."/>
              </div>
            </div>
          </div>
        <div class="col-md-3 mt-2 mb-2">
          <label for="retrato" class="form-label">Retrato</label>
          <img alt="retrato" id="retrato-img" src="{{asset("storage/retratos/{$personaje->retrato}")}}" class="img-fluid" width="185" height="180">
          <input type="file" name="retrato" class="form-control" id="retrato">
          @error('retrato')
          <small style="color: red">{{$message}}</small>
          @enderror
        </div>
        </div>
      </div>
    </div>{{-- Fin sección de datos básicos y retrato --}}

    {{-- Descripción breve --}}
    <div class="card card-dark card-outline mt-4">
      <div class="card-body">
        <x-textarea-input name="descripcion_short" label="Descripción breve" :value="$personaje->descripcion_short" rows="2" />
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
                <x-textarea-input name="descripcion" label="Descripción física" :value="$personaje->descripcion" />
                <x-textarea-input name="salud" label="Salud" :value="$personaje->salud" />
                <x-textarea-input name="personalidad" label="Personalidad" :value="$personaje->personalidad" />
              </div>
              <div class="col-md-6">
                <x-textarea-input name="deseos" label="Principales deseos" :value="$personaje->deseos" />
                <x-textarea-input name="miedos" label="Principales miedos" :value="$personaje->miedos" />
                <x-textarea-input name="magia" label="Habilidades Mágicas" :value="$personaje->magia" />
              </div>
            </div>
          </div>

          {{-- PESTAÑA 2 --}}
          <div class="tab-pane fade" id="tab-social" role="tabpanel">
            <x-textarea-input name="educacion" label="Educación y cultura" :value="$personaje->educacion" />
            <x-textarea-input name="religion" label="Religión" :value="$personaje->religion" />
            <x-textarea-input name="familia" label="Familia" :value="$personaje->familia" />
            <x-textarea-input name="politica" label="Política y títulos" :value="$personaje->politica" />
          </div>

          {{-- PESTAÑA 3 --}}
          <div class="tab-pane fade" id="tab-historia" role="tabpanel">
            <x-textarea-input name="historia" label="Historia" :value="$personaje->historia" class="summernote" rows="10" />
            <x-textarea-input name="otros" label="Otros detalles" :value="$personaje->otros" />
          </div>
        </div>
      </div>
    </div>{{-- Fin panel de pestañas --}}

  </form>

</section>
<!-- /.content -->
@endsection

@section('specific-scripts')
<script>
  $(function() {
    // Summernote
    $('.summernote').summernote({
      height: 300
    })

    $('.summernote-lite').summernote({
      height: 150
    })

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